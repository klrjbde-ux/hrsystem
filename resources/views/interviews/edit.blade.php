@extends('master')

@section('content')

<main id="main" class="main">
    <div class="container-fluid">

        <section class="section d-flex flex-column align-items-center justify-content-center py-4">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-8">

                        <div class="card mb-5 w-100">
                            <div class="card-body">

                                <h4 class="mb-4">Edit Candidate</h4>

                                <form action="{{ route('interviews.update', $interview->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="mb-3">
                                        <label class="form-label">Name</label>
                                        <input type="text" name="name" value="{{ $interview->name }}" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Applied Job</label>
                                        <input type="text" name="applied_job" value="{{ $interview->applied_job }}" class="form-control" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Current Salary</label>
                                        <input type="number" name="current_salary" value="{{ $interview->current_salary }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Expected Salary</label>
                                        <input type="number" name="expected_salary" value="{{ $interview->expected_salary }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Joining Date</label>
                                        <input type="date" name="joining_date" value="{{ $interview->joining_date }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Interview Date</label>
                                        <input type="date" name="interview_date" value="{{ $interview->interview_date }}" class="form-control">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="Shortlisted" {{ $interview->status=='Shortlisted'?'selected':'' }}>Shortlisted</option>
                                            <option value="Hired" {{ $interview->status=='Hired'?'selected':'' }}>Hired</option>
                                            <option value="Rejected" {{ $interview->status=='Rejected'?'selected':'' }}>Rejected</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control" rows="3">{{ $interview->remarks }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        @if($interview->cv)
                                        <p>Current CV:
                                            <a href="{{ asset('uploads/cv/'.$interview->cv) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </p>
                                        @endif
                                        <label class="form-label">Upload New CV</label>
                                        <input type="file" name="cv" class="form-control">
                                    </div>

                                    <button class="btn btn-success w-100">Update Candidate</button>
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