<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Models\TaskHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Project $project)
    {
        $tasks = $project->tasks()
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
        return view('tasks.show', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:to_do,in_progress,completed,qa,qa_passed,qa_failed',
        ]);

        $task->update($request->all());
        $task->status_changed_at = now();
        $task->save();

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

        $department = $user->department;
        $isSQA = $department === 'SQA';

        $allowedMoves = [
            'to_do' => ['in_progress'],
            'in_progress' => ['to_do', 'completed', 'qa'],
            'completed' => ['in_progress', 'qa'],
            'qa' => [],
            'qa_passed' => [],
            'qa_failed' => ['in_progress'],
        ];

        if ($isSQA) {
            $allowedMoves['qa'] = ['qa_passed', 'qa_failed'];
            $allowedMoves['qa_failed'] = ['in_progress'];
        }

        if (!$isSQA && $task->user_id != $user->id) {
            return response()->json(['error' => 'You are not allowed to move this task.'], 403);
        }

        if (!isset($allowedMoves[$currentStatus]) || !in_array($newStatus, $allowedMoves[$currentStatus])) {
            return response()->json(['error' => 'Invalid task movement.'], 403);
        }

        // --------------- Store history BEFORE updating task ---------------
        \App\Models\TaskHistory::create([
            'task_id' => $task->id,
            'old_status' => $currentStatus,
            'new_status' => $newStatus,
            'changed_by' => $user->id,
            'assigned_to' => $task->user_id,
        ]);

        // Update task
        $task->status = $newStatus;
        $task->status_changed_at = now();
        $task->save();

        return response()->json([
            'message' => 'Task status updated successfully.',
            'status' => $task->status,
            'status_changed_at' => $task->status_changed_at,
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
            $comments = $task->comments()->with(['user', 'previous_versions', 'parent'])->get()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'user_id' => $c->user_id,
                    'user_name' => $c->user->name ?? 'Unknown',
                    'comment' => $c->comment,
                    'is_edited' => $c->previous_versions->count() > 0,
                    'previous_versions' => $c->previous_versions->pluck('old_comment')->toArray(),
                    'parent_comment' => $c->parent->comment ?? null,
                ];
            });

            return response()->json($comments);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch comments.',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function addComment(Request $request, Task $task)
    {
        $request->validate([
            'comment' => 'required|string'
        ]);

        $comment = $task->comments()->create([
            'user_id' => auth()->id(),
            'comment' => $request->comment,
            'parent_comment_id' => $request->parent_comment_id ?? null
        ]);

        return response()->json($comment);
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

        // Update comment and is_edited
        $comment->comment = $request->comment;
        $comment->is_edited = true;  // <-- make sure this is explicitly set
        $comment->save();            // <-- explicitly save

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
}
