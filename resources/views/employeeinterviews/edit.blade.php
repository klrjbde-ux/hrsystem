@extends('master')
@section('content')

<main id="main" class="main">
    <div class="container-fluid">

        <section class="section d-flex flex-column align-items-center justify-content-center py-4">

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12">

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">

                                <h5 class="card-title text-center pb-3 fs-4">Edit Interview</h5>

                                <form class="row g-3"
                                    action="{{ route('employeeinterviews.update', $interview->id) }}"
                                    method="POST"
                                    enctype="multipart/form-data">

                                    @csrf
                                    @method('PUT')

                                    {{-- Candidate Name --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Candidate Name <span class="text-danger">*</span></label>
                                        <input type="text" name="candidate_name" class="form-control"
                                            value="{{ old('candidate_name', $interview->candidate_name) }}" required>
                                    </div>

                                    {{-- Applied For Job --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Applied For Job <span class="text-danger">*</span></label>
                                        <input type="text" name="applied_for_job" class="form-control"
                                            value="{{ old('applied_for_job', $interview->applied_for_job) }}" required>
                                    </div>

                                    {{-- Current Salary --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Current Salary</label>
                                        <input type="number" name="current_salary" class="form-control"
                                            value="{{ old('current_salary', $interview->current_salary) }}">
                                    </div>

                                    {{-- Expected Salary --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Expected Salary</label>
                                        <input type="number" name="expected_salary" class="form-control"
                                            value="{{ old('expected_salary', $interview->expected_salary) }}">
                                    </div>

                                    {{-- Interview Date --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Interview Date <span class="text-danger">*</span></label>
                                        <input type="date" name="interview_date" class="form-control"
                                            value="{{ old('interview_date', $interview->interview_date) }}" required>
                                    </div>

                                    {{-- Date of Joining --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Date of Joining</label>
                                        <input type="date" name="date_of_joining" class="form-control"
                                            value="{{ old('date_of_joining', $interview->date_of_joining) }}">
                                    </div>

                                    {{-- Interview Status --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Interview Status <span class="text-danger">*</span></label>
                                        <select name="interview_status" class="form-select" required>
                                            <option value="Shortlisted" {{ $interview->interview_status == 'Shortlisted' ? 'selected' : '' }}>
                                                Shortlisted
                                            </option>
                                            <option value="Hired" {{ $interview->interview_status == 'Hired' ? 'selected' : '' }}>
                                                Hired
                                            </option>
                                            <option value="Rejected" {{ $interview->interview_status == 'Rejected' ? 'selected' : '' }}>
                                                Rejected
                                            </option>
                                        </select>
                                    </div>

                                    {{-- Interview Remarks --}}
                                    <div class="col-md-6">
                                        <label class="form-label">Interview Remarks</label>
                                        <textarea name="interview_remarks" class="form-control" rows="3">{{ old('interview_remarks', $interview->interview_remarks) }}</textarea>
                                    </div>

                                    {{-- CV --}}
                                    <div class="col-md-6">
                                        <label class="form-label">CV (Upload New if needed)</label>
                                        <input type="file" name="cv" class="form-control">
                                        @if($interview->cv)
                                        <small class="text-muted">
                                            Current CV:
                                            <a href="{{ asset('storage/'.$interview->cv) }}" target="_blank">View</a>
                                        </small>
                                        @endif
                                    </div>

                                    {{-- Submit --}}
                                    <div class="col-12">
                                        <button class="btn btn-primary">Update Interview</button>
                                        <a href="{{ route('employeeinterviews.index') }}" class="btn btn-secondary">
                                            Back
                                        </a>
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