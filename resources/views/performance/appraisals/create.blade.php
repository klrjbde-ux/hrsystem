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
                                <h5 class="card-title"> Appraisal Form</h5>
                                @if (session('danger'))<div class="alert alert-danger">{{ session('danger') }}</div>@endif
                                <form action="{{ route('performance.appraisals.store') }}" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Employee</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="employee_id" required>
                                                <option value="">Select Employee</option>
                                                @foreach($employees as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Performance Review</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="performance_review_id" required>
                                                <option value="">Select Review</option>
                                                @foreach($reviews as $r)
                                                    <option value="{{ $r->id }}" {{ old('performance_review_id') == $r->id ? 'selected' : '' }}>
                                                        {{ $r->period_start->format('d M Y') }} – {{ $r->period_end->format('d M Y') }}
                                                        @if($r->employee) ({{ $r->employee->firstname }} {{ $r->employee->lastname }})@endif
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('performance_review_id')<div class="text-danger">{{ $message }}</div>@enderror
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Reviewer</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="reviewer_id">
                                                <option value="">— Select —</option>
                                                @foreach($reviewers as $emp)
                                                    <option value="{{ $emp->id }}" {{ old('reviewer_id') == $emp->id ? 'selected' : '' }}>{{ $emp->firstname }} {{ $emp->lastname }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Rating</label>
                                        <div class="col-sm-10">
                                            <input type="number" class="form-control" name="rating" value="{{ old('rating') }}" min="0" max="10" step="0.1" placeholder="0–10">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Review Date</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" name="review_date" value="{{ old('review_date', date('Y-m-d')) }}">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Comments</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="comments" rows="2">{{ old('comments') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Recommendations</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" name="recommendations" rows="2">{{ old('recommendations') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Save</button>
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
