@extends('master')
@section('content')
@section('people_management_select','active');
<main id="main" class="main">
    <div class="container">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-12 d-flex flex-column align-items-center justify-content-center">
                        <div class="card mb-3" style="width: 100%; max-width: 960px;">
                            <div class="card-body">
                                <h5 class="card-title text-center pb-0 fs-4">Employee Details</h5>
                                <div class="pt-4 pb-2">
                                    <h4>Personal Information</h4>
                                    <hr>
                                    <p><strong>Name:</strong> {{ $employee->firstname }} {{ $employee->lastname }}</p>
                                    <p><strong>Email:</strong> {{ $employee->personal_email }}</p>
                                    <p><strong>Username:</strong> {{ $employee->user_name }}</p>
                                    <p><strong>Gender:</strong> {{ $employee->gender }}</p>
                                    <p><strong>Date of Birth:</strong> {{ $employee->dob }}</p>

                                    <h4>Employment Data</h4>
                                    <hr>
                                    <p><strong>Employement Type:</strong>{{ $employee->empType->type ?? 'N/A' }}</p>
                                    <p><strong>Employement Status:</strong>{{ $employee->empStatus->status ?? 'N/A' }}</p>
                                    <p><strong>Designation:</strong>{{ $employee->Designation->designation_name ?? 'N/A' }}</p>
                                    <p><strong>Department:</strong>{{ $employee->Department->department_name ?? 'N/A'}}</p>
                                    <p><strong>Joining Date:</strong> {{ $employee->joining_date }}</p>
                                    <p><strong>Gross Salary:</strong> {{ $employee->gross_salary }}</p>

                                    <h4>Contact Information</h4>
                                    <hr>
                                    <p><strong>Contact Number:</strong> {{ $employee->contact_no }}</p>
                                    <p><strong>Identity Number:</strong> {{ $employee->identity_no }}</p>
                                    <p><strong>Permanent Address:</strong> {{ $employee->permanent_address }}</p>
                                    <p><strong>Current Address:</strong> {{ $employee->current_address }}</p>

                                    <h4>Emergency Contact</h4>
                                    <hr>
                                    <p><strong>Emergency Contact Number:</strong> {{ $employee->emergency_contact }}</p>
                                    <p><strong>Relation:</strong>{{ $employee->employeeContactRelation->contact_name ?? 'N/A' }}</p>
                                    <p><strong>Emergency Contact Address:</strong> {{ $employee->emergency_contact_address }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>
</main>
@endsection