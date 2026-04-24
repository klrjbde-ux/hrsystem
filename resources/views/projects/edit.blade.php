@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">
    <div class="container">
        <h3 class="mb-4 shadow-sm p-3 rounded bg-white">Edit Project</h3>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ route('projects.update', $project->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Project Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $project->name) }}" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Site Link --}}
                    <div class="mb-3">
                        <label for="site_link" class="form-label">Site Link</label>
                        <input type="url" name="site_link" id="site_link" class="form-control" value="{{ old('site_link', $project->site_link) }}">
                        @error('site_link')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Project File --}}
                    <div class="mb-3">
                        <label for="project_file" class="form-label">Project File</label>
                        <input type="file" name="project_file" id="project_file" class="form-control">
                        @error('project_file')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror

                        {{-- Show existing file with download --}}
                        @if($project->project_file)
                            <p class="mt-2">
                                Existing File: 
                                <a href="{{ asset('storage/' . $project->project_file) }}" target="_blank" class="btn btn-sm btn-success" download>
                                    Download
                                </a>
                            </p>
                        @endif
                    </div>

                    {{-- Start Date --}}
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control"
                               value="{{ old('start_date', \Carbon\Carbon::parse($project->start_date)->format('Y-m-d')) }}">
                        @error('start_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control"
                               value="{{ old('end_date', \Carbon\Carbon::parse($project->end_date)->format('Y-m-d')) }}">
                        @error('end_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="not_started" {{ old('status', $project->status) == 'not_started' ? 'selected' : '' }}>Not Started</option>
                            <option value="in_progress" {{ old('status', $project->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status', $project->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Update Project</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
