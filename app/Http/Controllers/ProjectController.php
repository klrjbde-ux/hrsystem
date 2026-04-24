<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
{
    $user = Auth::user();

    // Projects: jo user ne banaye + jisme team member hai + jinke tasks assign hain
    $ownedIds = $user->projects()->pluck('id');
    $teamMemberIds = $user->projectMembers()->pluck('project_teams.project_id');
    $taskProjectIds = $user->tasks()->distinct()->pluck('project_id');

    $projectIds = $ownedIds->merge($teamMemberIds)->merge($taskProjectIds)->unique()->filter();

    $projects = Project::whereIn('id', $projectIds)
        ->withCount([
            'tasks as to_do_tasks' => function ($query) {
                $query->where('status', 'to_do');
            },
            'tasks as in_progress_tasks' => function ($query) {
                $query->where('status', 'in_progress');
            },
            'tasks as completed_tasks' => function ($query) {
                $query->where('status', 'completed');
            }
        ])->get();

    // User tasks grouped by project
    $userTasks = [];
    foreach ($projects as $project) {
        $userTasks[$project->id] = $project->tasks()->where('user_id', auth()->id())->get();
    }

    return view('projects.index', compact('projects', 'userTasks'));
}


    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
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
            'budget' => $request->budget,
            'site_link' => $request->site_link,
            'project_file' => $filePath,
        ]);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
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

        $teamMembers = $project->users()->get();
        $users = User::all();

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
            'end_date' => 'nullable|date',
            'status' => 'required|in:not_started,in_progress,completed',
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
        if ($project->project_file && Storage::disk('public')->exists($project->project_file)) {
            Storage::disk('public')->delete($project->project_file);
        }

        $project->delete();

        return redirect()->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }

    public function addMember(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'user_id' => 'required|exists:users,id',
        ]);

        $project = Project::find($request->project_id);
        $project->teamProjects()->attach($request->user_id);

        return redirect()->back()->with('success', 'User added successfully.');
    }
}
