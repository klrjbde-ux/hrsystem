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
                        <p class="card-text"><strong>Create Project:</strong> {{ \Carbon\Carbon::parse($project->created_at)->format('Y-m-d') }}</p>
                        <p class="card-text"><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($project->start_date)->format('Y-m-d') }}</p>
                        <p class="card-text"><strong>End Date:</strong> {{ \Carbon\Carbon::parse($project->end_date)->format('Y-m-d') }}</p>
                        <!-- <p class="card-text">
                            <strong>Status:</strong>
                            {{ $project->status == 'pending' ? 'Pending' : ($project->status == 'on_going' ? 'In Progress' : 'Completed') }}
                        </p> -->
                        @php
                        $derivedStatus = ($project->total_tasks > 0 && $project->qa_passed_tasks == $project->total_tasks)
                        ? 'completed'
                        : 'in_progress';
                        @endphp

                        <p class="card-text">
                            <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $derivedStatus)) }}
                            &nbsp;|&nbsp;
                            <strong>Priority:</strong>
                            <span class="badge 
        {{ $project->priority == 'low' ? 'bg-success' : ($project->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($project->priority) }}
                            </span>
                        </p>

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
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Team Members</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <form action="{{ route('projects.addMember') }}" method="POST">
                            @csrf
                            <input type="hidden" name="project_id" value="{{ $project->id }}">

                            <label class="form-label mb-2">Select Users</label>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <select id="departmentFilter" class="form-select">
                                        <option value="">All Departments</option>
                                        @foreach($departments as $dept)
                                        <option value="{{ $dept }}">{{ $dept }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <select id="designationFilter" class="form-select">
                                        <option value="">All Designations</option>
                                        @foreach($designations as $desig)
                                        <option value="{{ $desig }}">{{ $desig }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row g-2">
                                @foreach ($users as $user)
                                @php
                                $isMember = $teamMembers->contains($user->id);
                                @endphp

                                <div class="col-md-6 user-card"
                                    data-department="{{ optional($user->employee)->department }}"
                                    data-designation="{{ optional($user->employee)->designation }}">

                                    <div class="form-check p-2 border rounded {{ $isMember ? 'bg-light text-muted' : '' }}">

                                        <!-- Checkbox -->
                                        <input
                                            class="form-check-input"
                                            type="checkbox"
                                            name="user_ids[]"
                                            value="{{ $user->id }}"
                                            id="user_{{ $user->id }}"
                                            {{ $isMember ? 'disabled' : '' }}>

                                        <label class="form-check-label fw-semibold" for="user_{{ $user->id }}">
                                            {{ $user->name }}

                                            <small class="d-block text-muted">{{ $user->email }}</small>

                                            <small class="d-block mt-1">

                                                <span class="badge bg-info text-dark">
                                                    {{ $user->employee->designation ?? 'Not set' }}
                                                </span>
                                            </small>
                                        </label>


                                        @if($isMember)
                                        <div class="d-flex justify-content-between align-items-center mt-2">
                                            <span class="badge bg-secondary">Already Added</span>


                                            <button
                                                type="button"
                                                class="btn btn-sm btn-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#removeMemberModal"
                                                onclick="setRemoveData({{ $project->id }}, {{ $user->id }})">
                                                Remove
                                            </button>
                                        </div>
                                        @endif

                                    </div>
                                </div>
                                @endforeach
                            </div>

                            <div class="modal-footer mt-3">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Add Selected Users</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <form id="removeMemberForm" action="{{ route('projects.removeMember') }}" method="POST">
            @csrf
            <input type="hidden" name="project_id" id="remove_project_id">
            <input type="hidden" name="user_id" id="remove_user_id">
        </form>
        <div class="modal fade" id="removeMemberModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">Remove Team Member</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        Are you sure you want to remove this team member from the project?
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" onclick="submitRemoveForm()">
                            Yes, Remove
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@section('js')
<script>
    function setRemoveData(projectId, userId) {
        document.getElementById('remove_project_id').value = projectId;
        document.getElementById('remove_user_id').value = userId;
    }

    function submitRemoveForm() {
        document.getElementById('removeMemberForm').submit();
    }

    document.addEventListener('DOMContentLoaded', function() {

        const deptFilter = document.getElementById('departmentFilter');
        const desigFilter = document.getElementById('designationFilter');

        if (!deptFilter || !desigFilter) return;

        function filterUsers() {
            const cards = document.querySelectorAll('.user-card');

            const dept = deptFilter.value.toLowerCase().trim();
            const desig = desigFilter.value.toLowerCase().trim();

            cards.forEach(card => {
                const cardDept = (card.dataset.department || '').toLowerCase().trim();
                const cardDesig = (card.dataset.designation || '').toLowerCase().trim();

                const matchDept = !dept || cardDept === dept;
                const matchDesig = !desig || cardDesig === desig;
                card.style.display = (matchDept && matchDesig) ? '' : 'none';
            });
        }

        deptFilter.addEventListener('change', filterUsers);
        desigFilter.addEventListener('change', filterUsers);
    });
</script>
@endsection