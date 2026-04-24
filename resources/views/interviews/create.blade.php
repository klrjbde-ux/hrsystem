@extends('master')
@section('content')

<main class="main" id="main">
    <div class="container-fluid">

        <section class="section d-flex flex-column align-items-center justify-content-center py-4">

            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12">

                        <div class="card mb-5 shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Add Candidate</h5>
                            </div>
                            <div class="card-body">

                                {{-- Display validation errors --}}
                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                <form action="{{ route('interviews.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="mb-3">
                                        <label for="name" class="form-label">Candidate Name <span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" id="name" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="applied_job" class="form-label">Applied Job <span class="text-danger">*</span></label>
                                        <input type="text" name="applied_job" class="form-control" id="applied_job" required>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="current_salary" class="form-label">Current Salary</label>
                                            <input type="number" name="current_salary" class="form-control" id="current_salary">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="expected_salary" class="form-label">Expected Salary</label>
                                            <input type="number" name="expected_salary" class="form-control" id="expected_salary">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="joining_date" class="form-label">Date of Joining</label>
                                            <input type="date" name="joining_date" class="form-control" id="joining_date">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="interview_date" class="form-label">Interview Date</label>
                                            <input type="date" name="interview_date" class="form-control" id="interview_date">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="status" class="form-label">Interview Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="Shortlisted">Shortlisted</option>
                                            <option value="Hired">Hired</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label for="remarks" class="form-label">Remarks</label>
                                        <textarea name="remarks" class="form-control" id="remarks" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="cv" class="form-label">Upload CV</label>
                                        <input type="file" name="cv" class="form-control" id="cv">
                                    </div>

                                    <button type="submit" class="btn btn-primary w-100">Save Candidate</button>
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