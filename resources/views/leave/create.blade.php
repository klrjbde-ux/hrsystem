@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">
  <div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
      <div class="container">
        @if(session('success'))
        <div id="success-message" class="alert alert-success" role="alert">
          {{ session('success') }}
        </div>
        @endif
        <div class="row justify-content-center">
          <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
            <div class="col-12 d-flex flex-column align-items-start justify-content-center">
              <div class="card mb-6" style="width: 100%; max-width: 960px;">
                <div class="card-body">
                  <div class="pt-4 pb-2">
                    <h5 class="card-title text-center pb-0 fs-4">Leave Form</h5>
                  </div>
                  <form class="row g-3 needs-validation" validate action="{{ Route('adminleave') }}" method="post">
                    @csrf
                    <!-- Employee Data -->
                    <div class="row col-12">
                      <div class="col-md-6">
                        <label for="employee" class="form-label">Employee Name<span class="text-danger">*</span></label>
                        <select class="form-select" name="employee" id="employee" required>
                          <option value="">Choose...</option>
                          @foreach($employee as $employee)
                          <option value="{{ $employee->id }}">{{ $employee->firstname }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback">Please, Select your name!</div>
                      </div>
                      <div class="col-md-6">
                        <label for="leavetype" class="form-label">Leave Type<span class="text-danger">*</span></label>
                        <select class="form-select" name="leavetype" id="leavetype" required>
                          <option value="">Choose...</option>
                          @foreach($leaves as $leave)
                          <option value="{{ $leave->id }}">{{ $leave->leave_types }}</option>
                          @endforeach
                        </select>
                        <div class="invalid-feedback">Please, enter Leave Type!</div>
                      </div>
                    </div>
                    <div class="row col-12">
                      <div class="col-md-6">
                        <label for="name" class="form-label">Status<span class="text-danger">*</span></label>
                        <select class="form-select" name="status" id="status" required>
                          <option value="Pending">Pending</option>
                          <option value="Approved">Approved</option>
                          <option value="Disline">Dicline</option>
                        </select>
                        <div class="invalid-feedback">Pleae! select a Status</div>
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

                    <div class="row pt-20px">
                      <div class="col-3 col-md-4 offset-4">
                        <button class="btn btn-primary w-100" type="submit">Submit Leave Request</button>
                      </div>
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
@section('js')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var successMessage = document.getElementById('success-message');
    if (successMessage) {
      successMessage.style.display = 'block';
      setTimeout(function() {
        successMessage.style.display = 'none';
      }, 3000);
    }
  });
</script>
@endsection