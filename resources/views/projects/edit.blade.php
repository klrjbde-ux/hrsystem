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
                        <input type="date" name="start_date" id="start_date"
    value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}"
    max="{{ now()->toDateString() }}"
    class="form-control" required>
                        @error('start_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    {{-- End Date --}}
                    <div class="mb-3">
                        <label for="end_date" class="form-label">End Date</label>
<input type="date" name="end_date" id="end_date"
    value="{{ old('end_date', optional($project->end_date)->format('Y-m-d')) }}"
    min="{{ now()->toDateString() }}"
    class="form-control" required>                       
                        @error('end_date')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- Priority --}}
                    <div class="mb-3">
                        <label for="priority" class="form-label">Priority</label>
                        <select name="priority" id="priority" class="form-select" required>
                            <option value="low" {{ old('priority', $project->priority) == 'low' ? 'selected' : '' }}>Low</option>
                            <option value="medium" {{ old('priority', $project->priority) == 'medium' ? 'selected' : '' }}>Medium</option>
                            <option value="high" {{ old('priority', $project->priority) == 'high' ? 'selected' : '' }}>High</option>
                        </select>
                        @error('priority')
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
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');

    if (!start || !end) return;

    function syncDates() {
        let startDate = start.value;

        if (startDate) {
            // end must be >= start
            end.min = startDate > "{{ now()->toDateString() }}"
                ? "{{ now()->toDateString() }}"
                : startDate;

            if (end.value && end.value < end.min) {
                end.value = end.min;
            }
        }
    }

    syncDates();
    start.addEventListener('change', syncDates);
});
</script>
@endsection