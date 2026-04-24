@extends('master')
@section('content')
@section('people-management-active', 'active')
@section('people-management_addemp_active', 'active')

@section('add-employee-active', 'active')

<main id="main" class="main">

    <div class="pagetitle">
        <h1>Add Employee </h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">People Management</li>
                <li class="breadcrumb-item active">Add Employee</li>
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
                        <form class="row g-3 needs-validation" novalidate action="{{ route('employee.update', $employee->id) }}"
                            method="post" enctype="multipart/form-data">
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
                                        placeholder="Enter First Name" maxlength="20" pattern="[A-Za-z ]+"
                                        value="{{ $employee->firstname }}" required>
                                    <div class="invalid-feedback" id="firstname-feedback">
                                        <!-- Default message for empty field -->
                                        <span id="empty-message-firstname">Please enter a first name</span>
                                        <!-- Message for invalid characters -->
                                        <span id="pattern-message-firstname" style="display: none;">Only letters and
                                            spaces are allowed</span>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <label for="lasstname" class="form-label">Last Name<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="lastname" maxlength="20" pattern="[A-Za-z ]+"
                                        class="form-control" id="lastname" placeholder="Enter Last Name"
                                        value="{{ $employee->lastname }}" required>
                                    <div class="invalid-feedback" id="lastname-feedback">
                                        <span id="empty-message-lastname">Please enter a last name</span>
                                        <span id="pattern-message-lastname" style="display: none;">Only letters and
                                            spaces are allowed</span>
                                    </div>

                                </div>
                            </div>

                            <!-- Email & Username -->
                            <div class="row col-12 gap">
                                <div class="col-md-6">
                                    <label for="yourEmail" class="form-label">Email<span
                                            class="text-danger">*</span></label>
                                    <input type="email" name="personal_email" class="form-control" id="yourEmail"
                                        placeholder="Enter Email Address" maxlength="50" required
                                        value="{{ $employee->personal_email }}">
                                    <div class="invalid-feedback" id="email-feedback">
                                        <span id="empty-message-email" style="display: none;">Please enter an email
                                            address</span>
                                        <span id="pattern-message-email" style="display: none;">Please enter a valid
                                            email address</span>
                                    </div>
                                    @error('personal_email')
                                    <div class="text-danger"> {{ $message }}</div>
                                    @enderror
                                </div>

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
                            </div>
                            <!-- Gender & DOB -->
                            <div class="row col-12 gap">
                                <div class="col-md-6">

                                    <label for="dateofbirth" class="form-label">Date of Birth<span
                                            class="text-danger">*</span></label>
                                    <input type="Date" name="dob" class="form-control" id="dateofbirth"
                                        value="{{ $employee->dob }}" required>
                                    <div class="invalid-feedback">
                                        Please select
                                        a date of birth </div>
                                </div>
                                <div class="col-md-6">
                                    <label for="gender" class="form-label">Profile Picture <span
                                            class="text-danger">*</span></label>
                                    <input type="file" name="image" class="form-control" id="image"
                                        accept=".jpg, .jpeg, .png">


                                </div>

                            </div>
                            <!-- emp_type && Emp_Status -->


                            <div class="row col-md-12 gap">
                                <div class="gap pt-2">
                                    <h4 style="margin-left: 2px">Employment data</h4>
                                </div>
                                <div class="col-md-6">
                                    <label for="emp-type" class="form-label">Employment Type<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="emp_type" required>
                                        <option value="">Select Employment Type</option>

                                        @foreach ($empTypes as $type)
                                        <option value="{{ $type->type }}"
                                            {{ $employee->emp_type === $type->type ? 'selected' : '' }}>
                                            {{ $type->type }}
                                        </option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">Please select an employment type</div>
                                </div>

                                <div class="col-md-6">
                                    <label for="emp-status" class="form-label">Employment Status<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="emp_status" required>
                                        <option value="">Select Employment Status</option>

                                        @foreach ($EmpStatus as $status)
                                        <option value="{{ $status->status }}"
                                            {{ $employee->emp_status === $status->status ? 'selected' : '' }}>
                                            {{ $status->status }}
                                        </option>
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
                                    <select class="form-select" name="designation" required>
                                        @foreach ($designations as $desig)
                                        <option value="{{ $desig->designation_name }}"
                                            {{ $employee->designation === $desig->designation_name ? 'selected' : '' }}>
                                            {{ $desig->designation_name }}
                                        </option>
                                        @endforeach
                                    </select>


                                    <div class="invalid-feedback">Please select a designation</div>
                                </div>
                                <div class="col-md-6">
                                    <label for="department" class="form-label">Department<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="department" required>
                                        @foreach ($departments as $depart)
                                        <option value="{{ $depart->department_name }}"
                                            {{ $employee->department === $depart->department_name ? 'selected' : '' }}>
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
                                            id="joiningdate" value="{{ $employee->joining_date }}" required>
                                        <div class="invalid-feedback" id="joiningdate-feedback">
                                            <span id="empty-message-joiningdate" style="display: none;">Please select
                                                a joining date</span>
                                            <span id="pattern-message-joiningdate" style="display: none;"> Joining
                                                date cannot be earlier than the date of birth</span>
                                            <span id="pattern-message-joiningdate-after" style="display: none;">
                                                Joining date can be at least 15 years after the date of birth </span>
                                            <span id="future-date-message-joiningdate" style="display: none;">
                                                Joining date cannot be a future date</span>
                                        </div>

                                    </div>

                                </div>

                                <div class="col-md-6">
                                    <label for="gross_salary" class="form-label">Gross Salary<span
                                            class="text-danger">*</span></label>
                                    <input type="number" name="gross_salary" class="form-control" id="gross_salary"
                                        min="5000" max="20000000000" value="{{ $employee->gross_salary ?? '' }}"
                                        required>
                                    <div class="invalid-feedback" id="salary-feedback">

                                        <span id="empty-message-salary" style="display: none;">Please enter a salary</span>
                                        <span id="pattern-message-salary" style="display: none;">Salary must be at
                                            least 5000</span>
                                        <span id="exceed-pattern-message-salary" style="display: none;">Salary cannot
                                            be exceed form 20000000000</span>
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
                                    <h4 style="margin-left:1px">Contact Information</h4>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Contact#<span
                                                class="text-danger">*</span></label>
                                        <input type="tel" class="form-control mt-2" id="contact_number"
                                            placeholder="Enter Contact Number" name="contact_no" pattern="03[0-9]{9}"
                                            minlength="11" maxlength="11" value="{{ $employee->contact_no }}" required>
                                        <div class="invalid-feedback" id="contact-feedback">
                                            <span id="empty-message-contact" style="display: none;">Please enter a
                                                contact number</span>
                                            <span id="pattern-message-contact" style="display: none;">Please enter a
                                                valid contact number (e.g 03012345678)</span>
                                        </div>
                                        @error('contact_no')
                                        <div class="text-danger"> {{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">Identity#<span
                                                class="text-danger">*</span></label>

                                        <input type="tel" class="form-control mt-2"
                                            placeholder="Enter Identity Number" name="identity_no" minlength="13"
                                            id="identity" maxlength="15" pattern="\d{5}-\d{7}-\d{1}" value="{{ $employee->identity_no }}" required>
                                        <div class="invalid-feedback" id="identity-feedback">
                                            <span id="empty-message-identity" style="display: none;">Please enter an
                                                identity number</span>
                                            <span id="pattern-message-identity" style="display: none;">Please enter
                                                exactly 13 digits</span>
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
                                    <h4 style="margin-left: 2px">Emergency Contact</h4>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="control-label">Emergency Contact#<span
                                            class="text-danger">*</span></label>
                                    <input type="tel" class="form-control mt-2"
                                        placeholder="Enter Emergency Contact Number" name="emergency_contact"
                                        id="emergency_contact" pattern="03[0-9]{9}" minlength="11" maxlength="11"
                                        value="{{ $employee->emergency_contact }}" required>
                                    <div class="invalid-feedback" id="contact-feedback">
                                        <span id="empty-message-emergency-contact" style="display: none;">Please enter
                                            a emergency contact number</span>
                                        <span id="pattern-message-emergency-contact" style="display: none;">Please
                                            enter a valid emergency contact number (e.g 03012345678)</span>
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="relation">Emergency Contact Relationship<span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" name="relation" required>
                                        <option value="">Select Relation</option>

                                        @foreach ($relations as $relation)
                                        <option value="{{ $relation->contact_name }}"
                                            {{ $employee->relation === $relation->contact_name ? 'selected' : '' }}>
                                            {{ $relation->contact_name }}
                                        </option>
                                        @endforeach
                                    </select>

                                    <div class="invalid-feedback">Please enter a emergency contact relationship</div>
                                </div>
                            </div>
                            <div class="row col-md-12 gap">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label class="control-label">Emergency Contact Address<span
                                            class="text-danger">*</span></label>
                                    <textarea rows="4" class="form-control mt-2" placeholder="Enter Emergency Contact Address"
                                        name="emergency_contact_address" maxlength="191" id="ecaddress" required>{{ $employee->emergency_contact_address }}</textarea>
                                    <div class="invalid-feedback">Please enter an emergency contact address</div>
                                </div>
                            </div>

                            <div class="col-lg-3 col-md-3 col-sm-3">
                                <button class="btn btn-primary" type="submit">Update Employee</button>
                                <br>
                                <br>
                                <br>
                                <br>
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
        const dateofbirthField = document.getElementById('dateofbirth');
        const joiningDateField = document.getElementById('joiningdate');
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
            const emptyMessageJoiningDate = document.getElementById('empty-message-joiningdate');
            const patternMessageJoiningDate = document.getElementById('pattern-message-joiningdate');
            const patternMessageJoiningDateafter = document.getElementById('pattern-message-joiningdate-after');
            const futureDateMessageJoiningDate = document.getElementById('future-date-message-joiningdate');
            const emptymessagesalary = document.getElementById('empty-message-salary');
            const patternmessagesalary = document.getElementById('pattern-message-salary');
            const exceedpatternmessagesalary = document.getElementById('exceed-pattern-message-salary');
            const salaryValue = parseFloat(grossSalaryField.value);
            // Validate firstName field
            if (!grossSalaryField.value) {
                emptymessagesalary.style.display = 'block';
                patternmessagesalary.style.display = 'none';
                exceedpatternmessagesalary.style.display = 'none';
            } else if (salaryValue < 5000) {
                emptymessagesalary.style.display = 'none';
                patternmessagesalary.style.display = 'block';
                exceedpatternmessagesalary.style.display = 'none';
            } else if (salaryValue > 20000000000) {
                emptymessagesalary.style.display = 'none';
                patternmessagesalary.style.display = 'none';
                exceedpatternmessagesalary.style.display = 'block';
            } else {
                emptymessagesalary.style.display = 'none';
                patternmessagesalary.style.display = 'none';
                exceedpatternmessagesalary.style.display = 'none';
            }
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
            //             // Validate date of birth and joining date
            const dob = new Date(dateofbirthField.value);
            const joiningDate = new Date(joiningDateField.value);
            let today = new Date();
            today.setHours(0, 0, 0, 0);
            //   // Calculate the minimum valid date of birth for a 15-year difference
            const fifteenYearsBeforeJoining = new Date(joiningDate);
            fifteenYearsBeforeJoining.setFullYear(fifteenYearsBeforeJoining.getFullYear() - 15);

            if (!joiningDateField.value) {
                emptyMessageJoiningDate.style.display = 'block';
                patternMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDateafter.style.display = 'none';
                futureDateMessageJoiningDate.style.display = 'none';
            } else if (dob && joiningDate > today) {
                futureDateMessageJoiningDate.style.display = 'block';
                emptyMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDateafter.style.display = 'none';
            } else if (dob && joiningDate < dob) {
                emptyMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDate.style.display = 'block';
                patternMessageJoiningDateafter.style.display = 'none';
                futureDateMessageJoiningDate.style.display = 'none';

            } else if (dob && joiningDate.getTime() === dob.getTime()) {
                emptyMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDateafter.style.display = 'block';
                futureDateMessageJoiningDate.style.display = 'none';

            } else if (dob && dob > fifteenYearsBeforeJoining) {
                emptyMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDateafter.style.display = 'block';
                futureDateMessageJoiningDate.style.display = 'none';

            } else {
                emptyMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDate.style.display = 'none';
                patternMessageJoiningDateafter.style.display = 'none';
                futureDateMessageJoiningDate.style.display = 'none';
            }
        }

        function updateJoiningDateMinDate() {
            const dob = new Date(dateofbirthField.value);
            if (dob) {
                // Add 15 years to the date of birth
                const minDate = new Date(dob.setFullYear(dob.getFullYear() + 15)).toISOString().split('T')[0];
                joiningDateField.setAttribute('min', minDate);
            } else {
                joiningDateField.removeAttribute('min');
            }

            // Also update the max date for joiningDate
            const today = new Date().toISOString().split('T')[0];
            joiningDateField.setAttribute('max', today);
        }
        // Add event listener for dateofbirthField to update joiningDate min date
        dateofbirthField.addEventListener('input', function() {
            updateJoiningDateMinDate();
            updateFeedbackMessages();
        });

        // Add event listener for dateofbirthField to update joiningDate min date
        joiningDateField.addEventListener('input', function() {
            updateJoiningDateMinDate();
            updateFeedbackMessages();
        });
        // Define fields
        const fields = {
            firstName: document.getElementById('firstname'),
            lastName: document.getElementById('lastname'),
            yourEmail: document.getElementById('yourEmail'),

            gender: document.getElementById('gender'),
            dateofbirth: document.getElementById('dateofbirth'),
            grossSalary: document.getElementById('gross_salary'),
            empType: document.getElementById('emp-type'),
            empStatus: document.getElementById('emp-status'),
            designation: document.getElementById('designation'),
            department: document.getElementById('department'),
            joiningDate: document.getElementById('joiningdate'),
            role: document.getElementById('role'),
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
        dateofbirthField.addEventListener('input', updateFeedbackMessages);
        joiningDateField.addEventListener('input', updateFeedbackMessages);
        grossSalaryField.addEventListener('input', updateFeedbackMessages);

        // Set max date for date fields
        const today = new Date().toISOString().split('T')[0];
        dateofbirthField.setAttribute('max', today);
        joiningDateField.setAttribute('max', today);



    });
</script>

@endsection