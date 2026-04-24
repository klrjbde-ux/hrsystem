@extends('master')

@section('css')
@endsection

@section('content')
<main id="main" class="main">
    <div class="container-fluid">

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <!-- Project Details -->
            <div class="col-md-7">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">{{ $project->name }}</h5>
                        <p class="card-text">{{ $project->description }}</p>
                        <p class="card-text"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}</p>
                        <p class="card-text"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}</p>
                        <p class="card-text">
                            <strong>Status:</strong> 
                            {{ $project->status == 'pending' ? 'Pending' : ($project->status == 'on_going' ? 'In Progress' : 'Completed') }}
                        </p>
                        <p class="card-text"><strong>Budget:</strong> ${{ $project->budget }}</p>

                        <a href="{{ $project->site_link }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-globe"></i> Visit Website
                        </a>

                        <a href="{{ asset('storage/' . $project->project_file) }}" class="btn btn-success btn-sm" download>
                            <i class="bi bi-download"></i> Download File
                        </a>

                        <h5 class="mt-4">Project Progress</h5>
                        @php
                            $totalTasks = $project->tasks->count();
                            $completedTasks = $project->tasks->where('status', 'completed')->count();
                            $progress = $totalTasks > 0 ? ($completedTasks / $totalTasks) * 100 : 0;
                        @endphp
                        <div class="progress mb-4">
                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%;" 
                                 aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
                                {{ round($progress) }}%
                            </div>
                        </div>

                        <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Projects</a>
                    </div>
                </div>
            </div>

            <!-- Team Members -->
            <div class="col-md-5">
                <div class="card mb-4 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Team Members</h5>
                            @hasanyrole('admin|hr_manager')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#addMemberModal"> <i class="bi bi-plus-circle"></i> </button>
                            @endhasanyrole
                        </div>

                        <div class="row mt-3">
                            @forelse ($teamMembers as $user)
                                <div class="col-12 mb-3 d-flex">
                                    <div class="card w-100 h-100">
                                        <div class="card-body d-flex flex-column justify-content-between">
                                            <div>
                                                <p class="card-title fw-bolder mb-1">{{ $user->name }}</p>
                                                <small class="text-muted d-block">{{ $user->email }}</small>
                                            </div>
                                            <span class="badge bg-secondary mt-2 align-self-start">
                                                {{ ucfirst($user->employee->designation ?? 'Not set') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">No team members assigned yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div> <!-- End .row -->

        <!-- Add Team Member Modal -->
        <div class="modal fade" id="addMemberModal" tabindex="-1" aria-labelledby="addMemberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addMemberModalLabel">Add Team Member</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('projects.addMember') }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Select User</label>
                                <select class="form-select" name="user_id" id="user_id">
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Member</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div> <!-- End .container-fluid -->
</main>
@endsection

@section('js')
@endsection`