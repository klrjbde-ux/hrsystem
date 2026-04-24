@extends('master')

@section('content')

<main id="main" class="main">
    <div class="container-fluid py-4">

        {{-- Success Message --}}
        @if(session('success'))
        <div id="successAlert" class="alert alert-success">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(function() {
                let alert = document.getElementById('successAlert');
                if (alert) {
                    alert.style.transition = "opacity 0.3s";
                    alert.style.opacity = "0";
                    setTimeout(() => alert.remove(), 400);
                }
            }, 5000);
        </script>
        @endif

        <div class="card shadow-sm">
            <div class="card-body">

                {{-- Header --}}
                <div class="mb-4">
                    <h5 class="mb-0">Employee Interviews</h5>
                </div>

                {{-- Filters + Add Interview --}}
                <form method="GET"
                    action="{{ route('employeeinterviews.index') }}"
                    class="row g-2 align-items-end mb-4">

                    {{-- Candidate Name --}}
                    <div class="col-lg-3 col-md-3 col-sm-6">
                        <input type="text"
                            name="name"
                            value="{{ request('name') }}"
                            class="form-control form-control-sm"
                            placeholder="Candidate Name">
                    </div>

                    {{-- Status --}}
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <select name="status" class="form-select form-select-sm">
                            <option value="">All Status</option>
                            <option value="Shortlisted" {{ request('status') == 'Shortlisted' ? 'selected' : '' }}>Shortlisted</option>
                            <option value="Hired" {{ request('status') == 'Hired' ? 'selected' : '' }}>Hired</option>
                            <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    {{-- Interview Date --}}
                    <div class="col-lg-2 col-md-3 col-sm-6">
                        <input type="date"
                            name="interview_date"
                            value="{{ request('interview_date') }}"
                            class="form-control form-control-sm">
                    </div>

                    {{-- Buttons --}}
                    <div class="col-lg-4 col-md-3 col-sm-12 ps-lg-1">
                        <div class="d-flex gap-2 justify-content-lg-end justify-content-start">

                            <button type="submit" class="btn btn-primary btn-sm">
                                Filter
                            </button>

                            <a href="{{ route('employeeinterviews.index') }}"
                                class="btn btn-outline-secondary btn-sm">
                                Reset
                            </a>

                            @hasanyrole('admin|hr_manager')
                            <a href="{{ route('employeeinterviews.create') }}"
                                class="btn btn-success btn-sm">
                                Add Interview
                            </a>
                            @endhasanyrole

                        </div>
                    </div>

                </form>

                {{-- Interviews Table --}}
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Candidate</th>
                                <th>Job</th>
                                <th>Current Salary</th>
                                <th>Expected Salary</th>
                                <th>Interview Date</th>
                                <th>Joining Date</th>
                                <th>Status</th>
                                <th>Remarks</th>
                                <th>CV</th>
                                @hasanyrole('admin|hr_manager')
                                <th width="150">Action</th>
                                @endhasanyrole
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($interviews as $interview)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $interview->candidate_name }}</td>
                                <td>{{ $interview->applied_for_job }}</td>
                                <td>{{ $interview->current_salary ? number_format($interview->current_salary) : '-' }}</td>
                                <td>{{ $interview->expected_salary ? number_format($interview->expected_salary) : '-' }}</td>
                                <td>{{ \Carbon\Carbon::parse($interview->interview_date)->format('d M Y') }}</td>
                                <td>
                                    {{ $interview->date_of_joining
                                            ? \Carbon\Carbon::parse($interview->date_of_joining)->format('d M Y')
                                            : '-' }}
                                </td>

                                <td>
                                    <span class="badge 
                                            {{ $interview->interview_status == 'Hired' ? 'bg-success' :
                                               ($interview->interview_status == 'Rejected' ? 'bg-danger' :
                                               ($interview->interview_status == 'Shortlisted' ? 'bg-primary' : 'bg-warning')) }}">
                                        {{ $interview->interview_status }}
                                    </span>
                                </td>

                                <td>{{ $interview->interview_remarks ?? '-' }}</td>

                                <td>
                                    @if($interview->cv)
                                    <a href="{{ asset('storage/' . $interview->cv) }}"
                                        target="_blank"
                                        class="btn btn-sm btn-outline-info">
                                        View CV
                                    </a>
                                    @else
                                    <span class="text-muted">N/A</span>
                                    @endif
                                </td>

                                @hasanyrole('admin|hr_manager')
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('employeeinterviews.edit', $interview->id) }}"
                                            class="btn btn-sm btn-primary">
                                            Edit
                                        </a>

                                        <form action="{{ route('employeeinterviews.destroy', $interview->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Delete this interview?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                                @endhasanyrole
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted">
                                    No interviews found
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
</main>

@endsection