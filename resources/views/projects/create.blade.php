@extends('master')
@section('css')
@endsection
@section('content')

<main id="main" class="main">
    <div class="container mb-3">
        <h3 class="mb-4 shadow-sm p-3 rounded bg-white">Create Project</h3>
        <div class="card border-0 shadow-sm m-auto" style="max-width: 600px;">
            <div class="card-body">
                <form action="{{ route('projects.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Project Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" class="form-control" required>
                        @error('name')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description') }}</textarea>
                        @error('description')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Site Link --}}
                    <div class="mb-3">
                        <label for="site_link" class="form-label">Site Link</label>
                        <input type="url" name="site_link" id="site_link" value="{{ old('site_link') }}" class="form-control">
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
                    </div>

                    {{-- Start Date --}}
                    <div class="mb-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" class="form-control">
                        @error('start_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" class="form-control">
                        @error('end_date')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- Status --}}
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select" required>
                            <option value="not_started" {{ old('status') == 'not_started' ? 'selected' : '' }}>Not Started</option>
                            <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                        @error('status')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Create Project</button>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
