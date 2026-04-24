<main id="main" class="main">
  <div class="container">

    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-10  col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="d-flex justify-content-center py-4">
              <a href="index.html" class="logo d-flex align-items-center w-auto">
                <img src="assets/img/logo.png" alt="">
                <span class="d-none d-lg-block">HR-Management</span>
              </a>
            </div><!-- End Logo -->
            <div class="col-12 d-flex flex-column align-items-left justify-content-center">
              <div class="card mb-6" style="width: 100%; max-width: 960px;"> <!-- Adjust max-width as needed -->
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Employe Form</h5>
                    <p class="text-center small">Enter your personal details</p>
                  </div>

                  <form class="row g-3 needs-validation" novalidate action="{{url('addemployee')}}" method="post">
                    @csrf
                    <h4>Personal Information</h4>
                    <hr>
                    <!-- name -->
                    <div class="row col-12">
                      <div class="col-md-6">
                        <label for="firstname" class="form-label">First Name</label>
                        <input type="text" name="firstname" class="form-control" id="firstname" required>
                        <div class="invalid-feedback">Please, enter your name!</div>
                      </div>
                      <div class="col-md-6">
                        <label for="lasstname" class="form-label">Last Name</label>
                        <input type="text" name="lastname" class="form-control" id="lastname" required>
                        <div class="invalid-feedback">Please, enter your name!</div>
                      </div>
                    </div>
                    <!-- Email & Username -->
                    <div class="row col-12">
                      <div class="col-md-6">
                        <label for="yourEmail" class="form-label">Your Email</label>
                        <input type="email" name="personal_email" class="form-control" id="yourEmail" required>
                        <div class="invalid-feedback">Please enter a valid Email address!</div>
                      </div>
                      <div class="col-md-6">
                        <label for="yourUsername" class="form-label">Username</label>
                        <div class="input-group has-validation">
                          <span class="input-group-text" id="inputGroupPrepend">@</span>
                          <input type="text" name="user_name" class="form-control" id="yourUsername" required>
                          <div class="invalid-feedback">Please choose a username.</div>
                        </div>
                      </div>
                      <!-- Gender & DOB -->
                      <div class="row col-md-12">
                        <div class="col-md-6">
                          <label for="gender" class="form-label">Gender</label>
                          <select class="form-select" name="gender" id="gender" required>
                            <option value="">Choose...</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                          </select>
                          <div class="invalid-feedback">Please choose a gender.</div>
                        </div>
                        <div class="col-md-6">
                          <label for="date" class="form-label">Date of Birth</label>
                          <div class="input-group has-validation">
                            <input type="Date" name="dob" class="form-control" id="date" required>
                            <div class="invalid-feedback">Please choose a DOB.</div>
                          </div>
                        </div>

                      </div>
                      <!-- emp_type && Emp_Status -->
                      <h4>Employement data</h4>
                      <hr>
                      <div class=" row col-md-12">
                        <div class="col-md-6">
                          <label for="emp-type" class="form-label">Employement Type</label>
                          <select class="form-select" name="emp_type" id="emp-type" required>
                            <option value="">Choose...</option>
                            @props(['empTypes', 'EmpStatus', 'designation'])
                            @foreach($empTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->type }}</option>
                            @endforeach
                          </select>

                          <div class="invalid-feedback">Please choose EMPLOYEMENT type</div>
                        </div>
                        <div class="col-md-6">
                          <label for="emp-status" class="form-label">Employment Status</label>
                          <select class="form-select" name="emp_status" id="emp-status" required>
                            @foreach($EmpStatus as $status)
                            <option value="{{ $status->id }}">{{ $status->status }}</option>
                            @endforeach
                          </select>
                          <div class="invalid-feedback">Please choose EMPLOYEMENT STATUS.</div>

                        </div>
                      </div>
                      <div class=" row col-md-12">
                        <div class="col-md-6">
                          <label for="designation" class="form-label">Designation</label>
                          <select class="form-select" name="designation" id="designation" required>
                            @foreach($designation as $desig)
                            <option value="{{ $desig->id }}">{{ $desig->designation_name}}</option>
                            @endforeach
                          </select>
                          <div class="invalid-feedback">Please choose designation</div>
                        </div>
                        <div class="col-md-6">
                          <label for="emp-status" class="form-label">Department</label>
                          <select class="form-select" name="emp-status" id="emp-status" required>
                            <option value="">Choose...</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                          </select>
                          <div class="invalid-feedback">Please choose DEPARTMRNT.</div>
                        </div>
                      </div>
                      <div class=" row col-md-12">
                        <div class="col-md-6">
                          <label for="branch" class="form-label">Branch</label>
                          <select class="form-select" name="branch" id="branch" required>
                            <option value="">Choose...</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="b">C</option>
                          </select>
                          <div class="invalid-feedback">Please choose designation</div>
                        </div>
                        <div class="col-md-6">
                          <label for="joiningdate" class="form-label">Joining Date</label>
                          <div class="input-group has-validation">
                            <input type="Date" name="joining_date" class="form-control" id="joiningdate" required>
                            <div class="invalid-feedback">Please choose Joining date.</div>
                          </div>
                        </div>
                      </div>
                      <div class=" row col-md-12">
                        <div class="col-md-6">
                          <label for="manager" class="form-label">Manager</label>
                          <select class="form-select" name="manager" id="manager" required>
                            <option value="">Choose...</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="b">C</option>
                          </select>
                          <div class="invalid-feedback">Please choose designation</div>
                        </div>
                        <div class="col-md-6">
                          <label for="team" class="form-label">Team</label>
                          <select class="form-select" name="team" id="team" required>
                            <option value="">Choose...</option>
                            <option value="a">A</option>
                            <option value="b">B</option>
                            <option value="c">C</option>
                          </select>
                          <div class="invalid-feedback">Please choose DEPARTMRNT.</div>
                        </div>
                      </div>
                    </div>
                    <div class="col-12">
                      <button class="btn btn-primary w-100" type="submit">Add Employee</button>
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