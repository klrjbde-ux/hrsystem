@extends('master')

@section('content')

<main id="main" class="main">
    <div class="container-fluid">
        <section class="section py-4">
            <div class="row justify-content-center">
                <div class="col-12">

                    {{-- Success & Error Messages --}}
                    @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if (session('danger'))
                    <div class="alert alert-danger">
                        {{ session('danger') }}
                    </div>
                    @endif

                    <div class="card shadow-sm mb-4">
                        <div class="card-body">

                            {{-- Title --}}
                            <div class="mb-3">
                                <h5 class="card-title mb-0">Performance Reviews</h5>
                            </div>

                            {{-- 🔎 FILTER SECTION --}}
                            <form method="GET"
                                action="{{ route('performance.reviews.index') }}"
                                class="row g-3 align-items-end mb-4">

                                {{-- Employee Name --}}
                                <div class="col-md-2">
                                    <input type="text"
                                        name="employee_name"
                                        class="form-control"
                                        placeholder="Search Employee"
                                        value="{{ request('employee_name') }}">
                                </div>

                                {{-- Status --}}
                                <div class="col-md-2">
                                    <select name="status" class="form-select">
                                        <option value="">All Status</option>
                                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>

                                {{-- Period From --}}
                                <div class="col-md-2">
                                    <input type="date"
                                        name="period_from"
                                        class="form-control"
                                        value="{{ request('period_from') }}">
                                </div>

                                {{-- Period To --}}
                                <div class="col-md-2">
                                    <input type="date"
                                        name="period_to"
                                        class="form-control"
                                        value="{{ request('period_to') }}">
                                </div>

                                {{-- Buttons --}}
                                <div class="col-md-4 d-flex gap-2">

    <button type="submit" class="btn btn-primary" title="Filter">
        <i class="bi bi-funnel"></i>
    </button>

    <a href="{{ route('performance.reviews.index') }}"
        class="btn btn-secondary" title="Reset">
        <i class="bi bi-arrow-clockwise"></i>
    </a>

    <a href="{{ route('performance.reviews.create') }}"
        class="btn btn-success ms-auto" title="Add Review">
        <i class="bi bi-plus-circle"></i> Add Review
    </a>

</div>

                            </form>
                            {{-- END FILTER --}}

                            {{-- TABLE --}}
                            <div class="table-responsive">
                                <table class="table table-striped align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Employee</th>
                                            <th>Period</th>
                                            <th>Reviewer</th>
                                            <th>Rating</th>
                                            <th>Status</th>
                                            <th width="170">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($reviews as $r)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>

                                            <td>
                                                {{ ($r->employee->firstname ?? '') . ' ' . ($r->employee->lastname ?? '—') }}
                                            </td>

                                            <td>
                                                {{ $r->period_start->format('d M Y') }} –
                                                {{ $r->period_end->format('d M Y') }}
                                            </td>

                                            <td>
                                                {{ $r->reviewer
                                                        ? ($r->reviewer->firstname . ' ' . $r->reviewer->lastname)
                                                        : '—' }}
                                            </td>

                                            <td>
                                                {{ $r->overall_rating ?? '—' }}
                                            </td>

                                            <td>
                                                <span class="badge bg-{{
                                                        $r->status === 'completed' ? 'success' :
                                                        ($r->status === 'draft' ? 'secondary' : 'info')
                                                    }}">
                                                    {{ ucfirst(str_replace('_', ' ', $r->status)) }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('performance.reviews.show', $r->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>

                                                    <a href="{{ route('performance.reviews.edit', $r->id) }}"
                                                        class="btn btn-sm btn-info">
                                                        Edit
                                                    </a>

                                                    <form action="{{ route('performance.reviews.destroy', $r->id) }}"
                                                        method="POST"
                                                        onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                            Delete
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">
                                                No reviews found.
                                                <a href="{{ route('performance.reviews.create') }}">
                                                    Add Review
                                                </a>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- END TABLE --}}

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</main>

@endsection
