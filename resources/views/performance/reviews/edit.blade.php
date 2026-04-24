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
                                <h5 class="card-title">Edit Performance Review</h5>
                                @if (session('danger'))<div class="alert alert-danger">{{ session('danger') }}</div>@endif
                                <form action="{{ route('performance.reviews.update', $review->id) }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Employee</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="employee_id" required>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('employee_id', $review->employee_id) == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Period Start</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="period_start" value="{{ old('period_start', $review->period_start ? $review->period_start->format('Y-m-d') : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Period End</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="period_end" value="{{ old('period_end', $review->period_end ? $review->period_end->format('Y-m-d') : '') }}" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Reviewer</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="reviewer_id">
                                                <option value="">— Select —</option>
                                                @foreach($reviewers as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('reviewer_id', $review->reviewer_id) == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="status" required>
                                                <option value="draft" {{ old('status', $review->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="in_progress" {{ old('status', $review->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="completed" {{ old('status', $review->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Overall Rating</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="overall_rating" value="{{ old('overall_rating', $review->overall_rating) }}" min="0" max="10" step="0.1">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Strengths</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="strengths" rows="2">{{ old('strengths', $review->strengths) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Improvements</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="improvements" rows="2">{{ old('improvements', $review->improvements) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Comments</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="comments" rows="2">{{ old('comments', $review->comments) }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Update Review</button>
                                            <a href="{{ route('performance.reviews.index') }}" class="btn btn-secondary">Back</a>
                                            <a href="{{ route('performance.reviews.show', $review->id) }}" class="btn btn-outline-primary">View</a>
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
