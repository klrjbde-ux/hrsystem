@extends('master')
@section('content')
@section('css')
@endsection
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        @if (session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                        @if (session('danger'))<div class="alert alert-danger">{{ session('danger') }}</div>@endif

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Appraisal Tracking</h5>
                                    <a href="{{ route('performance.appraisals.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle"></i> Add Appraisal</a>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-striped datatable" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Employee</th>
                                                <th>Review Period</th>
                                                <th>Rating</th>
                                                <th>Review Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($appraisals as $ap)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>{{ ($ap->employee->firstname ?? '') . ' ' . ($ap->employee->lastname ?? '—') }}</td>
                                                    <td>@if($ap->performanceReview){{ $ap->performanceReview->period_start->format('d M Y') }} – {{ $ap->performanceReview->period_end->format('d M Y') }}@else—@endif</td>
                                                    <td>{{ $ap->rating ?? '—' }}</td>
                                                    <td>{{ $ap->review_date ? $ap->review_date->format('d M Y') : '—' }}</td>
                                                    <td><span class="badge bg-{{ $ap->status === 'completed' ? 'success' : ($ap->status === 'acknowledged' ? 'info' : 'warning') }}">{{ $ap->status }}</span></td>
                                                    <td>
                                                        <a href="{{ route('performance.appraisals.edit', $ap->id) }}" class="btn btn-info btn-sm">Edit</a>
                                                        <form action="{{ route('performance.appraisals.destroy', $ap->id) }}" method="post" class="d-inline" onsubmit="return confirm('Delete?');">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="text-center text-muted">No appraisals. <a href="{{ route('performance.appraisals.create') }}">Add one</a></td></tr>
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
