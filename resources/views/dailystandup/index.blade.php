@extends('master')

@section('content')
@section('css')
@endsection

<main id="main" class="main">
    <div class="container-fluid">
        <section class="section py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        @if (session('danger'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('danger') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        @endif

                        <div class="card shadow-sm mb-4">
                            <div class="card-body">

                                <!-- Header -->
                                <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                    <h5 class="card-title mb-0">Daily Standup – Meeting List</h5>

                                    <a href="{{ route('dailystandup.create') }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-plus-circle me-1"></i> Add Meeting
                                    </a>
                                </div>

                                <!-- Search Filters -->
                                <form method="GET" action="{{ route('dailystandup.index') }}"
                                    class="row g-2 align-items-center mb-4">

                                    <div class="col-12 col-md-3">
                                        <input type="date"
                                            name="date"
                                            class="form-control form-control-sm"
                                            value="{{ request('date') }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <input type="text"
                                            name="employee"
                                            class="form-control form-control-sm"
                                            placeholder="Search Employee"
                                            value="{{ request('employee') }}">
                                    </div>

                                    <div class="col-12 col-md-3">
                                        <select name="status" class="form-select form-select-sm">
                                            <option value="">All Status</option>
                                            <option value="present" {{ request('status')=='present'?'selected':'' }}>Present</option>
                                            <option value="absent" {{ request('status')=='absent'?'selected':'' }}>Absent</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-md-3 d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                            <i class="bi bi-search me-1"></i> Search
                                        </button>

                                        <a href="{{ route('dailystandup.index') }}"
                                            class="btn btn-outline-secondary btn-sm flex-fill">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                        </a>
                                    </div>

                                </form>

                                <!-- Table -->
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th>#</th>
                                                <th>Date</th>
                                                <th>Emp Name</th>
                                                <th>Status</th>
                                                <th>Remarks</th>
                                                <th class="text-center">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse ($meetings as $meeting)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>

                                                <td>
                                                    {{ $meeting->date ? \Carbon\Carbon::parse($meeting->date)->format('d M Y') : '—' }}
                                                </td>

                                                <td>
                                                    {{ ($meeting->employee->firstname ?? '') . ' ' . ($meeting->employee->lastname ?? '') ?: '—' }}
                                                </td>

                                                <td>
                                                    <span class="badge {{ $meeting->status == 'present' ? 'bg-success' : 'bg-danger' }}">
                                                        {{ ucfirst($meeting->status ?? '—') }}
                                                    </span>
                                                </td>

                                                <td>{{ Str::limit($meeting->remarks, 50) ?? '—' }}</td>

                                                <td class="text-center">
                                                    <div class="d-flex justify-content-center gap-2">
                                                        <a href="{{ route('dailystandup.edit', $meeting->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            Edit
                                                        </a>

                                                        <a href="{{ route('dailystandup.delete', $meeting->id) }}"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Are you sure you want to delete this meeting?');">
                                                            Delete
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-3">
                                                    No meetings found.
                                                    <a href="{{ route('dailystandup.create') }}">Add Meeting</a>
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

@endsection