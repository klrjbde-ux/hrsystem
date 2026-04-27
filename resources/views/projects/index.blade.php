@extends('master')

@section('content')
<main id="main" class="main">
    <div class="container">

        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center bg-white mb-4 shadow-sm p-2 rounded">
            <h3 class="mb-0">Projects</h3>
            @hasanyrole('admin|hr_manager')
            <a href="{{ route('projects.create') }}" class="btn btn-primary">
                Add Project
            </a>
            @endhasanyrole
        </div>

        <!-- Success Message -->
        @if(session('success'))
        <div id="success-alert" class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
        <!-- Priority Filter -->
        <form method="GET" action="{{ route('projects.index') }}" class="mb-3">
            <div class="row">
                <div class="col-md-3">
                    <select name="priority" class="form-select" onchange="this.form.submit()">
                        <option value="">-- All Priorities --</option>
                        <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                        <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                        <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                </div>
            </div>
        </form>
        <div class="row">
            @foreach($projects as $project)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $project->name }}</h5>

                        <p class="card-text">
                            {{ $project->description ?? 'No description available.' }}
                        </p>

                        @if($project->created_at)
                        <p class="card-text">
                            <strong>Created on:</strong>
                            {{ $project->created_at->format('Y-m-d') }}
                        </p>
                        @endif

                        @if($project->site_link)
                        <p>
                            <strong>Site:</strong>
                            <a href="{{ $project->site_link }}" target="_blank">
                                {{ $project->site_link }}
                            </a>
                        </p>
                        @endif

                        @if($project->project_file)
                        <p>
                            <strong>File:</strong>
                            <a href="{{ asset('storage/'.$project->project_file) }}"
                                target="_blank"
                                class="btn btn-sm btn-success">
                                Download File
                            </a>
                        </p>
                        @endif

                        @php
                        $derivedStatus = ($project->total_tasks > 0 && $project->qa_passed_tasks == $project->total_tasks)
                        ? 'completed'
                        : 'in_progress';
                        @endphp

                        <p class="card-text">
                            <strong>Status:</strong> {{ ucfirst(str_replace('_', ' ', $derivedStatus)) }}
                            &nbsp;|&nbsp;
                            <strong>Priority:</strong>
                            <span class="badge {{ $project->priority == 'low' ? 'bg-success' : ($project->priority == 'medium' ? 'bg-warning' : 'bg-danger') }}">
                                {{ ucfirst($project->priority) }}
                            </span>
                            <br>
                            <strong>Deadline:</strong>
                            @if($project->deadline_text === 'Deadline Passed')
                            <span class="text-danger">Deadline Passed</span>
                            @else
                            {{ $project->deadline_text }}
                            @endif
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('projects.tasks.index', $project->id) }}"
                                class="btn btn-primary btn-sm">
                                <i class="bi bi-list"></i>
                            </a>

                            <a href="{{ route('projects.show', $project->id) }}"
                                class="btn btn-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>

                            <a href="{{ route('projects.edit', $project->id) }}"
                                class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <form action="{{ route( 'projects.destroy', $project->id) }}"
                                method="POST"
                                class="d-inline delete-form">
                                @csrf
                                @method('DELETE')

                                <!-- ONLY CHANGE HERE -->
                                <button type="button"
                                    class="btn btn-danger btn-sm delete-btn">
                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</main>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Existing alert hide (UNCHANGED)
    setTimeout(function() {
        const alert = document.getElementById('success-alert');
        if (alert) {
            alert.style.display = 'none';
        }
    }, 2000);

    // Added SweetAlert popup
    document.addEventListener('DOMContentLoaded', function() {

        document.querySelectorAll('.delete-btn').forEach((button) => {
            button.addEventListener('click', function() {

                const form = this.closest('form');

                Swal.fire({
                    title: 'Are you sure?',
                    text: "Are you sure you want to delete this project?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });

            });
        });

    });
</script>