@extends('master')
@section('content')
    <main class="main" id="main">
        <div class="container">
            <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
                            <div class="col-12 d-flex flex-column align-items-start justify-content-center">
                                <div class="card mb-6" style="width: 100%; max-width: 960px;"> 
                                    <div class="card-body">
                                        <div class="pt-4 pb-2">
                                            <h5 class="card-title text-center pb-0 fs-4">Leave Form</h5>
                                        </div>
                                        <form class="row g-3 needs-validation" novalidate action="{{url('addleave')}}" method="post">
                                            @csrf
                                            <!-- Employee Data -->
                                            <div class="row col-12">
                                            <div class="col-md-6">
                                                <label for="employee" class="form-label">Employee Name<span class="text-danger">*</span></label>
                                                <select class="form-select" name="employee" id="employee" required>
                                                    <option value="">Choose...</option>
                                                    
                                                </select>
                                                <div class="invalid-feedback">Please, enter your name!</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="lastname" class="form-label">Last Name<span class="text-danger">*</span></label>
                                                <input type="text" name="lastname" class="form-control" id="lastname" placeholder="Enter Last Name" required>
                                                <div class="invalid-feedback">Please, enter your name!</div>
                                            </div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="col-md-6">
                                                <label for="leavetype" class="form-label">Leave Type<span class="text-danger">*</span></label>
                                                <select class="form-select" name="leavetype" id="leavetype" required>
                                                    <option value="">Choose...</option>
                                                    
                                                </select>
                                                <div class="invalid-feedback">Please, enter Leave Type!</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">CC Mail<span class="text-danger">*</span></label>
                                                <input type="email" name="email" class="form-control" id="email" placeholder="example@example.com" required>
                                                <div class="invalid-feedback">Please, enter your Mail!</div>
                                            </div>
                                        </div>
                                        <div class="row col-12">
                                            <div class="col-md-6">
                                                <label for="startdate" class="form-label">Start Date<span class="text-danger">*</span></label>
                                                <input type="date" name="startdate" class="form-control" id="startdate" required>
                                                <div class="invalid-feedback">Please, enter your Start Date!</div>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="enddate" class="form-label">End Date<span class="text-danger">*</span></label>
                                                <input type="date" name="enddate" class="form-control" id="enddate" required>
                                                <div class="invalid-feedback">Please, enter your End Date!</div>
                                            </div>
                                        </div>
                                            
                                            <div class="col-12">
                                            <button class="btn btn-primary w-100" type="submit">Submit Leave Request</button>
                                            </div>
                                        </form>
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