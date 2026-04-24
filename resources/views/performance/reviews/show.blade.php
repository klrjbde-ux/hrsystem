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
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="card-title mb-0">Performance Review Details</h5>
                                    <div>
                                        <a href="{{ route('performance.reviews.edit', $review->id) }}" class="btn btn-info btn-sm">Edit</a>
                                        <a href="{{ route('performance.reviews.index') }}" class="btn btn-secondary btn-sm">Back</a>
                                    </div>
                                </div>

                                <table class="table table-bordered">
                                    <tr><th style="width:180px">Employee</th><td>{{ ($review->employee->firstname ?? '') . ' ' . ($review->employee->lastname ?? '—') }}</td></tr>
                                    <tr><th>Period</th><td>{{ $review->period_start->format('d M Y') }} – {{ $review->period_end->format('d M Y') }}</td></tr>
                                    <tr><th>Reviewer</th><td>{{ $review->reviewer ? ($review->reviewer->firstname . ' ' . $review->reviewer->lastname) : '—' }}</td></tr>
                                    <tr><th>Overall Rating</th><td>{{ $review->overall_rating ?? '—' }}</td></tr>
                                    <tr><th>Status</th><td><span class="badge bg-{{ $review->status === 'completed' ? 'success' : 'secondary' }}">{{ $review->status }}</span></td></tr>
                                    <tr><th>Strengths</th><td>{{ $review->strengths ?? '—' }}</td></tr>
                                    <tr><th>Improvements</th><td>{{ $review->improvements ?? '—' }}</td></tr>
                                    <tr><th>Comments</th><td>{{ $review->comments ?? '—' }}</td></tr>
                                </table>

                                <h6 class="mt-4">Appraisals ({{ $review->appraisals->count() }})</h6>
                                @if($review->appraisals->count() > 0)
                                    <table class="table table-sm">
                                        <thead><tr><th>Employee</th><th>Rating</th><th>Status</th></tr></thead>
                                        <tbody>
                                            @foreach($review->appraisals as $ap)
                                                <tr>
                                                    <td>{{ $ap->employee ? ($ap->employee->firstname . ' ' . $ap->employee->lastname) : '—' }}</td>
                                                    <td>{{ $ap->rating ?? '—' }}</td>
                                                    <td>{{ $ap->status }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-muted">No appraisals yet.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
