@extends('master')

@section('content')

@php
use Illuminate\Support\Str;
@endphp

<main id="main" class="main">
    <div class="container-fluid">

        <section class="section d-flex flex-column align-items-center justify-content-center py-4">

            {{-- Success message --}}
            @if(session('success'))
            <div id="successAlert" class="alert alert-success">
                {{ session('success') }}
            </div>

            <script>
                setTimeout(function() {
                    let alert = document.getElementById('successAlert');
                    if (alert) {
                        alert.style.transition = "opacity 0.5s";
                        alert.style.opacity = "0";
                        setTimeout(() => alert.remove(), 500);
                    }
                }, 5000);
            </script>
            @endif


            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">

                                {{-- Add Candidate Button + Search --}}
                                @hasanyrole('admin|hr_manager')
                                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-3">
                                    <div class="mb-2 mb-md-0">
                                        <!-- Left empty or add title if needed -->
                                    </div>
                                    <div class="d-flex flex-column flex-md-row gap-2">
                                        <input type="text"
                                            id="interviewsSearch"
                                            class="form-control"
                                            style="max-width: 250px;"
                                            placeholder="Search...">
                                        <a class="btn btn-primary w-100 w-md-auto" href="{{ route('interviews.create') }}">
                                            Add Candidate
                                        </a>
                                    </div>
                                </div>
                                @endhasanyrole

                                {{-- Candidates Table --}}
                                <div class="table-responsive">
                                    <table id="interviewsTable" class="table table-bordered align-middle">

                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Name</th>
                                                <th>Applied Job</th>
                                                <th>Current Salary</th>
                                                <th>Expected Salary</th>
                                                <th>Status</th>
                                                <th>Interview Date</th>
                                                <th>Remarks</th>
                                                <th>CV</th>
                                                <th width="150">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($interviews as $interview)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $interview->name }}</td>
                                                <td>{{ $interview->applied_job }}</td>
                                                <td>{{ $interview->current_salary ?? '-' }}</td>
                                                <td>{{ $interview->expected_salary ?? '-' }}</td>
                                                <td>
                                                    <span class="badge 
                                                        {{ $interview->status == 'Hired' ? 'bg-success' : ($interview->status == 'Rejected' ? 'bg-danger' : 'bg-warning text-dark') }}">
                                                        {{ $interview->status }}
                                                    </span>
                                                </td>
                                                <td>{{ $interview->interview_date ? \Carbon\Carbon::parse($interview->interview_date)->format('d M Y') : '-' }}</td>
                                                <td style="max-width:200px;">
                                                    {{ Str::limit($interview->remarks, 50) }}
                                                </td>
                                                <td>
                                                    @if($interview->cv)
                                                    <a href="{{ asset('uploads/cv/'.$interview->cv) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        View
                                                    </a>
                                                    @else
                                                    <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    {{-- Edit --}}
                                                    <a href="{{ route('interviews.edit', $interview->id) }}" class="btn btn-sm btn-warning">
                                                        Edit
                                                    </a>

                                                    {{-- Delete --}}
                                                    <form action="{{ route('interviews.destroy', $interview->id) }}"
                                                        method="POST"
                                                        class="d-inline"
                                                        onsubmit="return confirm('Are you sure you want to delete this candidate?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    No candidates found
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>

                                    </table>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>
</main>

@section('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('interviewsSearch');
        const table = document.getElementById('interviewsTable');

        if (!searchInput || !table) return;

        searchInput.addEventListener('keyup', function() {
            const value = this.value.toLowerCase();
            const rows = table.querySelectorAll('tbody tr');

            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                row.style.display = text.indexOf(value) > -1 ? '' : 'none';
            });
        });
    });
</script>
@endsection

@endsection