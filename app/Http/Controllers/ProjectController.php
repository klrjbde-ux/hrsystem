<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Projects: owned + team member + assigned tasks
        $ownedIds = $user->projects()->pluck('id');
        $teamMemberIds = $user->projectMembers()->pluck('project_teams.project_id');
        $taskProjectIds = $user->tasks()->distinct()->pluck('project_id');

        $projectIds = $ownedIds
            ->merge($teamMemberIds)
            ->merge($taskProjectIds)
            ->unique()
            ->filter();

        // ✅ Start query (NO get yet)
        $projectsQuery = Project::whereIn('id', $projectIds)
            ->withCount([
                'tasks as to_do_tasks' => fn($q) => $q->where('status', 'to_do'),
                'tasks as in_progress_tasks' => fn($q) => $q->where('status', 'in_progress'),
                'tasks as completed_tasks' => fn($q) => $q->where('status', 'completed')
            ]);

        // ✅ Apply priority filter
        if ($request->filled('priority')) {
            $projectsQuery->where('priority', $request->priority);
        }

        // ✅ Get data
        $projects = $projectsQuery->get();

        // ✅ Sort: high → medium → low AND latest first
        $projects = $projects->sort(function ($a, $b) {
            $priorityOrder = ['high' => 3, 'medium' => 2, 'low' => 1];

            $priorityA = $priorityOrder[$a->priority] ?? 0;
            $priorityB = $priorityOrder[$b->priority] ?? 0;

            // Same priority → newest first
            if ($priorityA === $priorityB) {
                return $b->created_at <=> $a->created_at;
            }

            return $priorityB <=> $priorityA;
        });

        // User tasks grouped by project
        $userTasks = [];
        foreach ($projects as $project) {
            $userTasks[$project->id] = $project->tasks()
                ->where('user_id', auth()->id())
                ->get();
        }

        return view('projects.index', compact('projects', 'userTasks'));
    }

    public function create()
    {
        $priorities = ['low', 'medium', 'high'];

        return view('projects.create', compact('priorities'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'site_link' => 'nullable|url',
            'project_file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx|max:10240',
        ]);

        $filePath = $request->hasFile('project_file')
            ? $request->file('project_file')->store('projects', 'public')
            : null;

        Auth::user()->projects()->create([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority, // ← ADD THIS LINE
            'budget' => $request->budget,
            'site_link' => $request->site_link,
            'project_file' => $filePath,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }
    public function removeMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::findOrFail($request->project_id);

        // Remove user from project
        $project->teamProjects()->detach($request->user_id);

        return back()->with('success', 'Member removed successfully.');
    }
    public function show(Project $project)
    {
        $user = Auth::user();
        // Sirf wohi project dikhao jisme user owner, team member ya assigned task wala ho
        $canAccess = $project->user_id === $user->id
            || $project->users()->where('user_id', $user->id)->exists()
            || $project->tasks()->where('user_id', $user->id)->exists();

        if (!$canAccess) {
            abort(403, 'You do not have access to this project.');
        }

        $teamMembers = $project->users()
            ->whereHas('employee', function ($q) {
                $q->whereIn('department', ['Developer', 'SQA']);
            })
            ->get();

        $users = User::whereHas('employee', function ($q) {
            $q->whereIn('department', ['Developer', 'SQA']);
        })->get();

        // Only show tasks assigned to current user
        $userTasks = $project->tasks()->where('user_id', auth()->id())->get();

        return view('projects.show', compact('project', 'teamMembers', 'users', 'userTasks'));
    }

    public function edit(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Only project owner can edit this project.');
        }
        return view('projects.edit', compact('project'));
    }

    public function update(Request $request, Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Only project owner can update this project.');
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'end_date' => 'nullable|date',
            'status' => 'nullable|in:not_started,in_progress,completed',
            'budget' => 'nullable|numeric',
            'site_link' => 'nullable|url',
            'project_file' => 'nullable|file|mimes:zip,rar,pdf,doc,docx|max:10240',
        ]);

        $filePath = $project->project_file;

        if ($request->hasFile('project_file')) {
            if ($project->project_file && Storage::disk('public')->exists($project->project_file)) {
                Storage::disk('public')->delete($project->project_file);
            }
            $filePath = $request->file('project_file')->store('projects', 'public');
        }

        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
            'priority' => $request->priority, // ← ADD THIS LINE
            'budget' => $request->budget,
            'site_link' => $request->site_link,
            'project_file' => $filePath,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        if ($project->user_id !== Auth::id()) {
            abort(403, 'Only project owner can delete this project.');
        }

        // Detach all team members to avoid foreign key error
        $project->users()->detach();

        // Delete project file if exists
        if ($project->project_file && Storage::disk('public')->exists($project->project_file)) {
            Storage::disk('public')->delete($project->project_file);
        }

        // Delete the project
        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $project = Project::find($request->project_id);

        // Attach multiple users at once
        $project->teamProjects()->syncWithoutDetaching($request->user_ids);

        return redirect()->back()->with('success', 'Users added successfully.');
    }
}
