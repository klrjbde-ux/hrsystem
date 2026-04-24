@extends('master')
@section('content')

<main id="main" class="main">
    <div class="container-fluid">

        <section class="section">
            <div class="row justify-content-center">
                <div class="col-12">

                    @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                    @endif

                    <div class="card">
                        <div class="card-body">

                            <h5 class="card-title">Add Employee Interview</h5>

                            <form action="{{ route('employeeinterviews.store') }}"
                                method="POST" enctype="multipart/form-data">
                                @csrf

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Candidate Name</label>
                                        <input type="text" name="candidate_name" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Applied For Job</label>
                                        <input type="text" name="applied_for_job" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Upload CV</label>
                                        <input type="file" name="cv" class="form-control">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Interview Date</label>
                                        <input type="date" name="interview_date" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Current Salary</label>
                                        <input type="number" name="current_salary" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Expected Salary</label>
                                        <input type="number" name="expected_salary" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Date of Joining</label>
                                        <input type="date" name="date_of_joining" class="form-control" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Interview Status</label>
                                        <select name="interview_status" class="form-control">
                                            <option value="Shortlisted">Shortlisted</option>
                                            <option value="Hired">Hired</option>
                                            <option value="Rejected">Rejected</option>
                                        </select>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Interview Remarks</label>
                                        <textarea name="interview_remarks" class="form-control" rows="3" required></textarea>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            Save Interview
                                        </button>
                                    </div>

                                </div>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
</main>

@endsection