<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectTeam;
use App\Models\Task;
use App\Models\TaskHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()
            ->orderByRaw("FIELD(priority, 'high', 'medium', 'low')")
            ->latest()
            ->get()
            ->groupBy('status');

        $users = $project->users()->get();
        return view('tasks.index', compact('project', 'tasks', 'users'));
    }
    public function store(Request $request, Project $project)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed,qa,qa_passed,qa_failed',
        ]);

        $project->tasks()->create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'status' => $request->status,
            'status_changed_at' => now(),
        ]);

        return redirect()->route('projects.tasks.index', $project)
            ->with('success', 'Task created successfully.');
    }

    public function show(Task $task)
    {
        $task->load(['user', 'project', 'histories']);

        // Sort histories properly
        $histories = $task->histories->sortBy('created_at')->values();

        // Initialize durations
        $durationsSeconds = [
            'to_do' => 0,
            'in_progress' => 0,
            'qa' => 0,
            'qa_passed' => 0,
            'qa_failed' => 0,
            'completed' => 0,
        ];

        $cycleCount = 0;

        // Start from task creation
        $previousTime = $task->created_at;
        $previousStatus = 'to_do';

        foreach ($histories as $history) {

            // Normalize status
            $status = strtolower(str_replace(' ', '_', $previousStatus));

            // SAFE time calculation (no negative ever)
            if (isset($durationsSeconds[$status]) && $history->created_at && $previousTime) {

                if ($history->created_at >= $previousTime) {
                    $seconds = $previousTime->diffInSeconds($history->created_at);
                    $durationsSeconds[$status] += $seconds;
                }
            }

            // Count QA failed cycles
            if (strtolower($history->new_status) === 'qa_failed') {
                $cycleCount++;
            }

            // Move forward
            $previousTime = $history->created_at;
            $previousStatus = $history->new_status;
        }

        // Handle last status till now (SAFE)
        $lastStatus = strtolower(str_replace(' ', '_', $previousStatus));

        if (isset($durationsSeconds[$lastStatus]) && $previousTime) {

            if (now()->greaterThanOrEqualTo($previousTime)) {
                $durationsSeconds[$lastStatus] += $previousTime->diffInSeconds(now());
            }
        }

        // Format time (HH:MM:SS) with safety
        $format = function (int $seconds): string {
            $seconds = max(0, $seconds); // NEVER NEGATIVE
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            $s = $seconds % 60;
            return sprintf('%02d:%02d:%02d', $h, $m, $s);
        };

        $durations = collect($durationsSeconds)
            ->map(fn($sec) => $format((int) $sec))
            ->toArray();

        // Summary
        $summary = [
            'developer_time' => $format((int) ($durationsSeconds['in_progress'] ?? 0)),
            'qa_time' => $format((int) ($durationsSeconds['qa'] ?? 0)),
            'cycles' => $cycleCount,
        ];

        return view('tasks.show', compact('task', 'durations', 'summary'));
    }

    public function update(Request $request, Task $task)
    {
        $oldUser = $task->user_id;

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'user_id' => 'required|exists:users,id',
        ]);

        $task->update([
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
            'priority' => $request->priority,
            'user_id' => $request->user_id,
        ]);

        $task->status_changed_at = now();
        $task->save();


        if ($oldUser != $task->user_id) {
            TaskHistory::create([
                'task_id' => $task->id,
                'old_status' => $task->status,
                'new_status' => $task->status,
                'changed_by' => $request->user()->id,
                'assigned_to' => $task->user_id,
            ]);
        }

        return redirect()->route('projects.tasks.index', $task->project_id)
            ->with('success', 'Task updated successfully.');
    }
    public function updateStatus(Request $request, Task $task)
    {
        $user = auth()->user();

        $request->validate([
            'status' => 'required|in:to_do,in_progress,completed,qa,qa_passed,qa_failed',
        ]);

        $newStatus = $request->status;
        $currentStatus = $task->status;

        $isQaController = $user?->hasAnyRole(['sqa', 'qa']) ?? false;

        // Allowed transitions
        $allowedMoves = [
            'to_do' => ['in_progress'],
            'in_progress' => ['completed', 'qa'],
            'completed' => ['in_progress', 'qa'],
            'qa' => [],
            'qa_passed' => [],
            'qa_failed' => ['in_progress'],
        ];

        // QA/SQA can move only inside QA flow cards (no completed)
        if ($isQaController) {
            $allowedMoves = [
                'to_do' => [],
                'in_progress' => [],
                'completed' => [],
                'qa' => ['qa_passed', 'qa_failed'],
                'qa_passed' => ['qa', 'qa_failed'],
                'qa_failed' => ['qa', 'qa_passed'],
            ];
        } else {
            // Assigned employees can move in dev flow, including backwards
            $allowedMoves = [
                'to_do' => ['in_progress'],
                'in_progress' => ['completed', 'qa'],
                'completed' => ['in_progress', 'qa'],
                'qa' => ['completed', 'in_progress'],
                'qa_passed' => [],
                'qa_failed' => ['in_progress'],
            ];
        }

        //  Developers only move their own tasks
        if (!$isQaController && $task->user_id != $user->id) {
            return response()->json(['error' => 'Not allowed'], 403);
        }

        // Invalid move
        if (!isset($allowedMoves[$currentStatus]) || !in_array($newStatus, $allowedMoves[$currentStatus])) {
            return response()->json(['error' => 'Invalid move'], 403);
        }

        // Save history
        TaskHistory::create([
            'task_id' => $task->id,
            'old_status' => $currentStatus,
            'new_status' => $newStatus,
            'changed_by' => $user->id,
            'assigned_to' => $task->user_id,
        ]);

        // Update
        $task->update([
            'status' => $newStatus,
            'status_changed_at' => now(),
        ]);

        return response()->json([
            'message' => 'Updated',
            'status' => $task->status
        ]);
    }

    public function myTasks()
    {
        $tasks = Task::where('user_id', Auth::id())
            ->with('project')
            ->latest()
            ->get();

        return view('tasks.my_tasks', compact('tasks'));
    }

    public function getComments(Task $task)
    {
        try {
            $comments = $task->comments()
                ->with(['user', 'previous_versions', 'parent'])
                ->get()
                ->map(function ($c) {

                    return [
                        'id' => $c->id,
                        'user_id' => $c->user_id,
                        'user_name' => $c->user->name ?? 'Unknown',
                        'comment' => $c->comment,

                        'is_edited' => $c->previous_versions->count() > 0,
                        'previous_versions' => $c->previous_versions->pluck('old_comment')->toArray(),

                        'parent_comment' => $c->parent->comment ?? null,

                        'attachment' => $c->attachment
                            ? array_map(
                                fn($path) => asset('storage/' . $path),
                                is_array($c->attachment)
                                    ? $c->attachment
                                    : json_decode($c->attachment, true) ?? []
                            )
                            : [],
                    ];
                });

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'error' => 'Failed to fetch comments.'
            ], 500);
        }
    }

    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'nullable|string',
            'attachments.*' => 'image|mimes:jpg,jpeg,png|max:2048'
        ]);

        if (!$request->comment && !$request->hasFile('attachments')) {
            return response()->json(['error' => 'Comment or image required'], 422);
        }
        $comment = new \App\Models\Comment();
        $comment->task_id = $task->id;
        $comment->user_id = auth()->id();
        $comment->comment = $request->comment;
        $comment->parent_comment_id = $request->parent_comment_id ?? null;

        $paths = [];

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $paths[] = $file->store('comments', 'public');
            }
        }

        $comment->attachment = !empty($paths) ? json_encode($paths) : null;

        $comment->save();

        return response()->json([
            'id' => $comment->id,
            'comment' => $comment->comment,
            'attachment' => $comment->attachment
                ? array_map(function ($path) {
                    return str_starts_with($path, 'http')
                        ? $path
                        : asset('storage/' . $path);
                }, json_decode($comment->attachment, true) ?? [])
                : [],
            'user_id' => $comment->user_id
        ]);
    }
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = \App\Models\Comment::findOrFail($id);

        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Save old comment in history
        \App\Models\CommentHistory::create([
            'comment_id' => $comment->id,
            'old_comment' => $comment->comment
        ]);


        $existingImages = $comment->attachment ?? [];

        // If stored as JSON string
        if (is_string($existingImages)) {
            $existingImages = json_decode($existingImages, true) ?? [];
        }

        //  ADD new images (DO NOT replace)
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('comments', 'public');
                $existingImages[] = $path;
            }
        }

        //  Update comment
        $comment->comment = $request->comment;
        $comment->attachment = $existingImages; // merged images
        $comment->is_edited = true;

        $comment->save();

        return response()->json([
            'success' => true
        ]);
    }
    public function deleteComment($id)
    {
        $comment = \App\Models\Comment::findOrFail($id);

        // Only owner can delete
        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true
        ]);
    }
    public function commentHistory(\App\Models\Comment $comment)
    {
        // Fetch previous versions from CommentHistory table
        $history = $comment->previous_versions()->pluck('old_comment')->toArray();

        return response()->json([
            'history' => $history
        ]);
    }

    public function history(Task $task)
    {
        try {
            $history = $task->histories()->with('changedBy', 'assignedTo')->get()->map(function ($item) {
                return [
                    'old_status' => $item->old_status,
                    'new_status' => $item->new_status,
                    'assigned_to' => $item->assignedTo ? $item->assignedTo->name : 'N/A',
                    'changed_by' => $item->changedBy ? $item->changedBy->name : 'Unknown',
                    'created_at' => $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'N/A',
                ];
            });

            return response()->json($history);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Server error: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy(Task $task)
    {

        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'Unauthorized');
        }

        // Optional (if no cascade)
        TaskHistory::where('task_id', $task->id)->delete();
        \App\Models\Comment::where('task_id', $task->id)->delete();

        $task->delete();

        return redirect()->back()->with('success', 'Task deleted successfully.');
    }
    public function deleteImage(Request $request, $id)
    {
        $comment = \App\Models\Comment::findOrFail($id);

        if ($comment->user_id != auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $images = is_array($comment->attachment)
            ? $comment->attachment
            : json_decode($comment->attachment, true) ?? [];

        $index = $request->index;

        if (isset($images[$index])) {

            $path = $images[$index];

            if (str_starts_with($path, 'http')) {
                $path = str_replace(url('/storage/') . '/', '', $path);
            }

            \Storage::disk('public')->delete($path);

            unset($images[$index]);
        }

        $comment->attachment = !empty($images)
            ? array_values($images)
            : null;

        $comment->save();

        return response()->json(['success' => true]);
    }
}
