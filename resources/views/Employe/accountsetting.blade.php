@extends('master')
@section('content')
@section('people_management_select', 'active');
<main id="main" class="main">

    <div class="pagetitle">
        <h1>{{ Auth::user()->name  ?? 'N/A'}}</h1>
        <nav>
            <ol class="breadcrumb">
                <li><a href="">Your personal account
                    </a>
                </li>
            </ol>

        </nav>
    </div>

    <section class="section">

        <div id="warningMsg">
            <div class="alert alert-danger" role="alert" id="danger-message">
                Please fill all the fields Properly
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <form class="row g-3 needs-validation" novalidate
                            action="{{ route('employee.update', $employee->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <!-- name -->
                            <div class="row col-12 gap">
                                <div class="gap pt-2">
                                    <h4 style="margin-left: 2px">Personal Information</h4>
                                </div>
                                <div class="col-md-6">
                                    <label for="firstname" class="form-label">First Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="firstname" class="form-control" id="firstname"
                                        maxlength="20" pattern="[A-Za-z ]+" placeholder="Enter First Name" required
                                        value="{{ $employee->firstname }}">
                                    <div class="invalid-feedback" id="firstname-feedback">
                                        <span id="empty-message-firstname">Please enter a first name</span>
                                        <span id="pattern-message-firstname" style="display: none;">Only letters and spaces are allowed</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="lasstname" class="form-label">Last Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="lastname" class="form-control" id="lastname" maxlength="20" pattern="[A-Za-z ]+"
                                        placeholder="Enter Last Name" required value="{{ $employee->lastname }}">
                                    <div class="invalid-feedback" id="lastname-feedback">
                                        <span id="empty-message-lastname">Please enter a last name</span>
                                        <span id="pattern-message-lastname" style="display: none;">Only letters and spaces are allowed</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Email & Username -->
                            <div class="row col-12 gap">
                                <div class="col-md-6">
                                    <label for="yourEmail" class="form-label">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="personal_email" class="form-control" id="yourEmail"
                                        placeholder="Enter Email Address" maxlength="50" readonly
                                        value="{{ $employee->personal_email }}">
                                    <div class="invalid-feedback" id="email-feedback">
                                        <span id="empty-message-email" style="display: none;">Please enter an email address</span>
                                        <span id="pattern-message-email" style="display: none;">Please enter a valid email address</span>
                                    </div>
                                    @error('personal_email')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="yourUsername" class="form-label">Username<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group has-validation">
                                        <span class="input-group-text" id="inputGroupPrepend">@</span>
                                        <input type="text" name="user_name" class="form-control" id="yourUsername"
                                            placeholder="Enter User Name" required value="{{ $employee->user_name }}">
                                        <div class="invalid-feedback">Please enter a username</div>
                                        @error('user_name')
                                        <div class="text-danger"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Gender & DOB -->
                            <div class="row col-12 gap">
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Gender<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender">

                                        <option value="Male" {{ $employee->gender == 'Male' ? 'selected' : '' }}>Male
                                        </option>
                                        <option value="Female" {{ $employee->gender == 'Female' ? 'selected' : '' }}>
                                            Female
                                        </option>
                                    </select>
                                    <div class="invalid-feedback">Please select a gender</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Date of Birth<span
                                            class="text-danger">*</span></label>
                                    <input type="Date" name="dob" class="form-control" id="dateofbirth" required
                                        value="{{ $employee->dob }}">
                                    <div class="invalid-feedback">Please select a date of birth</div>
                                </div>
                            </div>
                            <div class="row col-md-12 gap">
                                <!-- emp_type && Emp_Status -->
                                <div class="gap pt-2">
                                    <h4 style="margin-left: 2px">Employment data</h4>
                                </div>
                                <div class="col-md-6">
                                    <label for="emp-status" class="form-label">Employment Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="emp_type" id="emp-type" required>
                                        @php

                                        $selectedEmpTypeId = $employee->empType->id ?? null;
                                        @endphp


                                        <option value="{{ $selectedEmpTypeId }}" selected>
                                            {{ $employee->empType->type ?? 'N/A' }}
                                        </option>

                                        <!-- Render the rest of the options, excluding the selected type -->
                                        @foreach ($empTypes as $type)
                                        @if ($type->id != $selectedEmpTypeId)
                                        <option value="{{ $type->id }}">{{ $type->type }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select an employment type</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="emp-status" class="form-label">Employment Status<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="emp_status" id="emp_status" required>
                                        @php

                                        $selectedEmpStatusId = $employee->empStatus->id ?? null;
                                        @endphp
                                        <option value="{{ $selectedEmpStatusId }}" selected>
                                            {{ $employee->empStatus->status ?? 'N/A' }}
                                        </option>

                                        <!-- Render the rest of the options, excluding the selected type -->
                                        @foreach ($EmpStatus as $status)
                                        @if ($status->id != $selectedEmpStatusId)
                                        <option value="{{ $status->id }}">{{ $status->status }}</option>
                                        @endif
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select an employment status</div>
                                </div>
                            </div>
                            <!-- DEsignation && Department -->
                            <div class="row col-md-12 gap">
                                <div class="col-md-6">
                                    <label for="designation" class="form-label">Designation<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="designation" id="designation" required>
                                        @foreach ($designations as $desig)
                                        <option value="{{ $desig->id }}"
                                            {{ isset($employee) && $employee->designation == $desig->id ? 'selected' : '' }}>
                                            {{ $desig->designation_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a designation</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="department" class="form-label">Department<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="department" id="department" required>
                                        @foreach ($departments as $depart)
                                        <option value="{{ $depart->id }}"
                                            {{ isset($employee) && $employee->department == $depart->id ? 'selected' : '' }}>
                                            {{ $depart->department_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a department</div>
                                </div>
                            </div>

                            <div class=" row col-md-12 gap">
                                <div class="col-md-6">
                                    <label for="joiningdate" class="form-label">Joining Date<span
                                            class="text-danger">*</span></label>
                                    <div class="input-group has-validation">
                                        <input type="Date" name="joining_date" class="form-control"
                                            id="joiningdate" required value="{{ $employee->joining_date }}">
                                        <div class="invalid-feedback">Please select a joining date</div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label for="gross_salary" class="form-label">Gross Salary<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="gross_salary" class="form-control"
                                        id="gross_salary" min="5000" max="20000000000"
                                        value="{{ $employee->gross_salary ?? '' }}" required>
                                    <div class="invalid-feedback" id="salary-feedback">
                                        <span id="empty-message-salary">Please enter a salary</span>
                                        <span id="pattern-message-salary" style="display: none;">Salary must be at least 5000</span>
                                        <span id="exceed-pattern-message-salary" style="display: none;">Salary cannot be exceed form 20000000000</span>
                                    </div>

                                </div>
                            </div>
                            <div class=" row col-md-12 gap">
                                <div class="col-md-6">
                                    <label for="role" class="form-label">Role<span
                                            class="text-danger">*</span></label>

                                    <select class="form-select" name="role" id="role" required>
                                        @foreach ($roles as $role)
                                        <option value="{{ $role->id }}"
                                            {{ $user_role && $user_role->id == $role->id ? 'selected' : '' }}>
                                            {{ $role->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback">Please select a role</div>
                                </div>
                            </div>
                            <div class="row col-md-12 gap">
                                <!-- Contact Info -->
                                <div class="gap pt-2">
                                    <h4 style="margin-left: 1px">Contact Information</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact#<span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control mt-2"
                                            placeholder="Enter Contact Number" name="contact_no" pattern="03[0-9]{9}"
                                            minlength="11" maxlength="11" required id="contact_number"
                                            value="{{ $employee->contact_no }}">
                                        <div class="invalid-feedback" id="contact-feedback">
                                            <span id="empty-message-contact" style="display: none;">Please enter a contact number</span>
                                            <span id="pattern-message-contact" style="display: none;">Please enter a valid contact number (e.g 03012345678)</span>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Identity#<span
                                                class="text-danger">*</span></label>

                                        <input type="tel" class="form-control mt-2"
                                            placeholder="Enter Identity Number" name="identity_no" minlength="13"
                                            id="identity" maxlength="15" pattern="\d{5}-\d{7}-\d{1}" required
                                            value="{{ $employee->identity_no }}">
                                        <div class="invalid-feedback" id="identity-feedback">
                                            <span id="empty-message-identity" style="display: none;">Please enter an identity number</span>
                                            <span id="pattern-message-identity" style="display: none;">Please enter exactly 13 digits</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row col-md-12 gap">
                                <div class="col-lg-6 col-md-6 col-sm-12 ">
                                    <div class="form-group">
                                        <label class="control-label">Permanent Address<span
                                                class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control mt-2" placeholder="Enter Permanent Address" name="permanent_address"
                                            maxlength="191" id="paddress" required>{{ $employee->permanent_address }}</textarea>
                                        <div class="invalid-feedback">Please enter a permanent address</div>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="form-group">
                                        <label class="control-label">Current Address<span
                                                class="text-danger">*</span></label>
                                        <textarea rows="4" class="form-control mt-2" placeholder="Enter Current Address" name="current_address"
                                            maxlength="191" id="caddress" required>{{ $employee->current_address }}</textarea>
                                        <div class="invalid-feedback">Please enter a current address</div>
                                    </div>
                                </div>
                            </div>
                            <div class="row col-md-12 gap">
                                <div class="gap pt-2">
                                    <h4 style="margin-left: 1px">Emergency Contact</h4>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="control-label">Emergency Contact#<span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control mt-2"
                                        placeholder="Enter Emergency Contact Number" name="emergency_contact"
                                        pattern="03[0-9]{9}" minlength="11" maxlength="11" id="emergency_contact"
                                        required value="{{ $employee->emergency_contact }}">
                                    <div class="invalid-feedback" id="contact-feedback">
                                        <span id="empty-message-emergency-contact" style="display: none;">Please enter a emergency contact number</span>
                                        <span id="pattern-message-emergency-contact" style="display: none;">Please enter a valid emergency contact number (e.g 03012345678)</span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="relation">Emergency Contact Relationship<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select gap1" name="relation" id="relation" required>
                                        <option value="{{ $employee->employeeContactRelation->id ?? 'N/A' }}">
                                            {{ $employee->employeeContactRelation->contact_name ?? 'N/A' }}
                                        </option>
                                        @foreach ($relations as $relation)
                                        <option value="{{ $relation->id }}">
                                            {{ $relation->contact_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row col-md-12 gap">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="control-label">Emergency Contact Address<span
                                            class="text-danger">*</span></label>
                                    <textarea rows="4" class="form-control mt-2" placeholder="Enter Emergency Contact Address"
                                        name="emergency_contact_address" maxlength="191" id="ecaddress" required>{{ $employee->emergency_contact_address }}</textarea>
                                    <div class="invalid-feedback">Please enter a emergency contact address</div>
                                </div>
                            </div>
                    </div>

                    <div class="ml-3">
                        <div class="row col-md-7">
                            <div class="">
                                <button class="btn btn-primary" type="submit">Update Employee</button>
                                <br>
                                <br>
                                <br>
                                <br>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
        </div>
    </section>

</main>
@endsection

@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const form = document.querySelector('form.needs-validation');
        const warningMsg = document.getElementById('warningMsg');
        const firstNameField = document.getElementById('firstname');
        const lastNameField = document.getElementById('lastname');
        const emailField = document.getElementById('yourEmail');
        const identityField = document.getElementById('identity');
        const contactField = document.getElementById('contact_number');
        const emergencycontactField = document.getElementById('emergency_contact');

        const grossSalaryField = document.getElementById('gross_salary');


        function formatIdentityNumber(value) {
            // Remove all non-digit characters
            value = value.replace(/\D/g, '');

            // Format the value with hyphens
            if (value.length <= 5) {
                return value;
            } else if (value.length <= 12) {
                return value.slice(0, 5) + '-' + value.slice(5);
            } else {
                return value.slice(0, 5) + '-' + value.slice(5, 12) + '-' + value.slice(12, 13);
            }
        }

        function updateFeedbackMessages() {
            // Retrieve feedback elements
            const emptyMessageFirstName = document.getElementById('empty-message-firstname');
            const patternMessageFirstName = document.getElementById('pattern-message-firstname');
            const emptyMessageLastName = document.getElementById('empty-message-lastname');
            const patternMessageLastName = document.getElementById('pattern-message-lastname');
            const emptyMessageEmail = document.getElementById('empty-message-email');
            const patternMessageEmail = document.getElementById('pattern-message-email');
            const emptyMessageIdentity = document.getElementById('empty-message-identity');
            const patternMessageIdentity = document.getElementById('pattern-message-identity');
            const emptyMessageContact = document.getElementById('empty-message-contact');
            const patternMessageContact = document.getElementById('pattern-message-contact');
            const emptyMessageEmergencyContact = document.getElementById('empty-message-emergency-contact');
            const patternMessageEmergencyContact = document.getElementById('pattern-message-emergency-contact');
            const emptyMessageSalary = document.getElementById('empty-message-salary');
            const patternMessageSalary = document.getElementById('pattern-message-salary');
            const exceedpatternMessageSalary = document.getElementById('exceed-pattern-message-salary');



            // Validate firstName field
            if (!firstNameField.value) {
                emptyMessageFirstName.style.display = 'block';
                patternMessageFirstName.style.display = 'none';
            } else if (!firstNameField.checkValidity()) {
                emptyMessageFirstName.style.display = 'none';
                patternMessageFirstName.style.display = 'block';
            } else {
                emptyMessageFirstName.style.display = 'none';
                patternMessageFirstName.style.display = 'none';
            }

            // Validate lastName field
            if (!lastNameField.value) {
                emptyMessageLastName.style.display = 'block';
                patternMessageLastName.style.display = 'none';

            } else if (!lastNameField.checkValidity()) {
                emptyMessageLastName.style.display = 'none';
                patternMessageLastName.style.display = 'block';
            } else {
                emptyMessageLastName.style.display = 'none';
                patternMessageLastName.style.display = 'none';
            }


            //validate email field
            if (!emailField.value) {
                emptyMessageEmail.style.display = 'block';
                patternMessageEmail.style.display = 'none';
            } else if (!emailField.checkValidity()) {
                emptyMessageEmail.style.display = 'none';
                patternMessageEmail.style.display = 'block';
            } else {
                emptyMessageEmail.style.display = 'none';
                patternMessageEmail.style.display = 'none';
            }

            // validate identity field
            if (!identityField.value) {
                emptyMessageIdentity.style.display = 'block';
                patternMessageIdentity.style.display = 'none';
            }
            // Check if the field value does not match the pattern
            else if (!identityField.checkValidity()) {
                emptyMessageIdentity.style.display = 'none';
                patternMessageIdentity.style.display = 'block';
            }
            // Valid field
            else {
                emptyMessageIdentity.style.display = 'none';
                patternMessageIdentity.style.display = 'none';
            }

            //validate contact field

            // Check if the field is empty
            if (!contactField.value) {
                emptyMessageContact.style.display = 'block';
                patternMessageContact.style.display = 'none';
            }
            // Check if the field value does not match the pattern
            else if (!contactField.checkValidity()) {
                emptyMessageContact.style.display = 'none';
                patternMessageContact.style.display = 'block';
            }
            // Valid field
            else {
                emptyMessageContact.style.display = 'none';
                patternMessageContact.style.display = 'none';
            }

            //Emergency contact 
            if (!emergencycontactField.value) {
                emptyMessageEmergencyContact.style.display = 'block';
                patternMessageEmergencyContact.style.display = 'none';
            }
            // Check if the field value does not match the pattern
            else if (!emergencycontactField.checkValidity()) {
                emptyMessageEmergencyContact.style.display = 'none';
                patternMessageEmergencyContact.style.display = 'block';
            }
            // Valid field
            else {
                emptyMessageEmergencyContact.style.display = 'none';
                patternMessageEmergencyContact.style.display = 'none';
            }


            // Validate salary field
            if (!grossSalaryField.value) {
                emptyMessageSalary.style.display = 'block';
                patternMessageSalary.style.display = 'none';
                exceedpatternMessageSalary.style.display = 'none';
            } else if (grossSalaryField.value < 5000) {
                emptyMessageSalary.style.display = 'none';
                patternMessageSalary.style.display = 'block';
                exceedpatternMessageSalary.style.display = 'none';
            } else if (grossSalaryField.value > 10000000000) {
                emptyMessageSalary.style.display = 'none';
                patternMessageSalary.style.display = 'none';
                exceedpatternMessageSalary.style.display = 'block';
            } else {
                emptyMessageSalary.style.display = 'none';
                patternMessageSalary.style.display = 'none';
                exceedpatternMessageSalary.style.display = 'none';
            }

        }


        // Define fields
        const fields = {
            grossSalary: document.getElementById('gross_salary'),
            firstName: document.getElementById('firstname'),
            lastName: document.getElementById('lastname'),
            yourEmail: document.getElementById('yourEmail'),
            yourUsername: document.getElementById('yourUsername'),
            contactNumber: document.getElementById('contact_number'),
            identity: document.getElementById('identity'),
            paddress: document.getElementById('paddress'),
            caddress: document.getElementById('caddress'),
            relation: document.getElementById('relation'),
            ecaddress: document.getElementById('ecaddress'),

            emergencyContact: document.getElementById('emergency_contact')
        };

        // Function to scroll to the first invalid field
        function scrollToFirstInvalidField() {
            for (const field in fields) {
                if (!fields[field].checkValidity()) {
                    fields[field].scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                    return; // Exit after scrolling to the first invalid field
                }
            }
        }

        form.addEventListener('submit', function(event) {
            updateFeedbackMessages();

            // Validate other fields
            if (!form.checkValidity() || !isValid) {
                event.preventDefault();
                event.stopPropagation();
                warningMsg.style.display = 'block';

                scrollToFirstInvalidField();
                setTimeout(() => {
                    if (warningMsg.style.display === 'block') {
                        warningMsg.style.display = 'none';
                    }
                }, 3000);
            } else {
                warningMsg.style.display = 'none';
            }

            form.classList.add('was-validated');
        }, false);

        firstNameField.addEventListener('input', updateFeedbackMessages);
        lastNameField.addEventListener('input', updateFeedbackMessages);
        emailField.addEventListener('input', updateFeedbackMessages);
        identityField.addEventListener('input', function() {
            identityField.value = formatIdentityNumber(identityField.value);
            updateFeedbackMessages();
        });
        contactField.addEventListener('input', updateFeedbackMessages);
        emergencycontactField.addEventListener('input', updateFeedbackMessages);
        grossSalaryField.addEventListener('input', updateFeedbackMessages);



        // Set max date for date fields
        const today = new Date().toISOString().split('T')[0];
        fields.dateofbirth.setAttribute('max', today);
        fields.joiningDate.setAttribute('max', today);


    });
</script>

@endsection