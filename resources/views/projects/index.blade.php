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
            @endhasanyrole

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="row">
                @foreach($projects as $project)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $project->name }}</h5>
                            <p class="card-text">
                                {{ $project->description ?? 'No description available.' }}
                            </p>
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
                            <p class="card-text">
                                <strong>Status:</strong>
                               {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                              <br>
                                <strong>Deadline:</strong>
                                @if($project->end_date && $project->end_date->isFuture())
                                    {{ $project->end_date->diffForHumans() }}
                                @else
                                    <span class="text-danger">Deadline Passed</span>
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

                                <form action="{{ route('projects.destroy', $project->id) }}" 
                                      method="POST" 
                                      class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="btn btn-danger btn-sm"
                                            onclick="return confirm('Are you sure you want to delete this project?')">
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
    
</div>

</main>
@endsection

    <script>
        // Wait 2 seconds (2000 milliseconds) then hide the alert
        setTimeout(function() {
            const alert = document.getElementById('success-alert');
            if(alert) {
                alert.style.display = 'none';
            }
        }, 2000);
    </script>
