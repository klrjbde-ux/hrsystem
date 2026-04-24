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
                                <h5 class="card-title">Edit Appraisal</h5>
                                @if (session('danger'))<div class="alert alert-danger">{{ session('danger') }}</div>@endif
                                <form action="{{ route('performance.appraisals.update', $appraisal->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Employee</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="employee_id" required>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('employee_id', $appraisal->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Performance Review</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="performance_review_id" required>
                                                @foreach($reviews as $r)
                                                    <option value="{{ $r->id }}" {{ old('performance_review_id', $appraisal->performance_review_id) == $r->id ? 'selected' : '' }}>{{ $r->period_start->format('d M Y') }} – {{ $r->period_end->format('d M Y') }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Reviewer</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="reviewer_id">
                                                <option value="">— Select —</option>
                                                @foreach($reviewers as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('reviewer_id', $appraisal->reviewer_id) == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Rating</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="rating" value="{{ old('rating', $appraisal->rating) }}" min="0" max="10" step="0.1">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Review Date</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="review_date" value="{{ old('review_date', $appraisal->review_date ? $appraisal->review_date->format('Y-m-d') : '') }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="status" required>
                                                <option value="pending" {{ old('status', $appraisal->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="completed" {{ old('status', $appraisal->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                                <option value="acknowledged" {{ old('status', $appraisal->status) == 'acknowledged' ? 'selected' : '' }}>Acknowledged</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Comments</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="comments" rows="2">{{ old('comments', $appraisal->comments) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Recommendations</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="recommendations" rows="2">{{ old('recommendations', $appraisal->recommendations) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Update</button>
                                            <a href="{{ route('performance.appraisals.index') }}" class="btn btn-secondary">Back</a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>
@endsection
