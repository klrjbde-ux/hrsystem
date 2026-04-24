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

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Performance Reports</h5>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="card bg-info text-white">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 opacity-75">Total Reviews</h6>
                                                <h3 class="card-title">{{ $stats['total_reviews'] ?? 0 }}</h3>
                                                <small>Completed: {{ $stats['completed_reviews'] ?? 0 }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-success text-white">
                                            <div class="card-body">
                                                <h6 class="card-subtitle mb-2 opacity-75">Total Appraisals</h6>
                                                <h3 class="card-title">{{ $stats['total_appraisals'] ?? 0 }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <h6 class="mb-2">Reviews by Status</h6>
                                <div class="table-responsive mb-4">
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>Status</th><th>Count</th></tr></thead>
                                        <tbody>
                                            @foreach($reviewsByStatus ?? [] as $status => $count)
                                                <tr><td>{{ ucfirst(str_replace('_', ' ', $status)) }}</td><td>{{ $count }}</td></tr>
                                            @endforeach
                                            @if(empty($reviewsByStatus))
                                                <tr><td colspan="2" class="text-muted">No reviews data</td></tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>

                                <h6 class="mb-2">Top Rated Employees (by Appraisal)</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered">
                                        <thead><tr><th>Employee</th><th>Avg Rating</th></tr></thead>
                                        <tbody>
                                            @forelse ($avgRatings ?? [] as $ar)
                                                <tr>
                                                    <td>{{ $ar->employee ? ($ar->employee->firstname . ' ' . $ar->employee->lastname) : '—' }}</td>
                                                    <td>{{ $ar->avg_rating ?? '—' }}</td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="2" class="text-muted">No rating data</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-3">
                                    <a href="{{ route('performance.reviews.index') }}" class="btn btn-outline-primary btn-sm">Reviews</a>
                                    <a href="{{ route('performance.appraisals.index') }}" class="btn btn-outline-primary btn-sm">Appraisals</a>
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
