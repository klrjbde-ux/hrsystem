@extends('master')
@section('content')
@section('people-management-active', 'active')
@section('people-management_addemp_active', 'active')
<meta name="csrf-token" content="{{ csrf_token() }}">
<style>

</style>

@section('add-employee-active', 'active')

<main id="main" class="main">

  <div class="pagetitle">
    <h1>Apply Leave</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item">Leave Management</li>
        <li class="breadcrumb-item active">Apply Leave</li>
      </ol>
    </nav>
  </div>

  <section class="section">
    @if(session('success'))
    <div class="alert alert-success" role="alert" id="alert-success">
      {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger" role="alert" id="alert-success">
      {{ session('error') }}
    </div>
    @endif
    @if(session('exceeds_limit'))
    <div id="customAlert" class="custom-alert">
      <div class="custom-alert-content">
        <p id="customAlertMessage">{{ session('exceeds_limit') }}</p>
        <div class="custom-alert-buttons">
          @if (session('leave_details'))
          @php
          $leaveDetails = session('leave_details');
          @endphp
          <form id="leave-form" method="POST" action="{{ url('ApplyUnpaidLeave') }}">
            @csrf
            <input type="hidden" name="employee" value="{{ $leaveDetails['employee'] }}">
            <input type="hidden" name="leavetype" value="{{ $leaveDetails['leavetype'] }}">
            <input type="hidden" name="startdate" value="{{ $leaveDetails['startdate'] }}">
            <input type="hidden" name="enddate" value="{{ $leaveDetails['enddate'] }}">
            <input type="hidden" name="reason" value="{{ $leaveDetails['reason'] }}">
            <button id="btnOk" type="submit" class="btn btn-ok">Yes</button>
            <span id="btnCancel" class="btn btn-cancel">Cancel</span>
          </form>
          @endif
        </div>
      </div>
    </div>
    @endif


    <div class="row">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body">
            <form class="row g-3 needs-validation" novalidate action="{{ url('storeapplyleave') }}"
              method="post" enctype="multipart/form-data">
              @csrf
              <div class="row col-12 gap">
                <div class="col-md-6">
                  <label for="employee" class="form-label">Employee Name<span class="text-danger">*</span></label>
                  <select class="form-select" name="employee" id="employee" required>
                    <option value="">Choose...</option>
                    @foreach($employee as $emp)
                    <option value="{{ $emp->id }}">{{ $emp->firstname }} {{ $emp->lastname }}</option>
                    @endforeach
                  </select>
                  @error('employee')
                  <div class="text-danger"> {{ $message }}</div>
                  @enderror
                </div>
                <div class="col-md-6">
                  <label for="leavetype" class="form-label">Leave Type<span class="text-danger">*</span></label>
                  <select class="form-select" name="leavetype" id="leavetype" required>
                    <option value="">Choose...</option>
                    @foreach($leaves as $leave)
                    <option value="{{ $leave->id }}">{{ $leave->Name }}</option>
                    @endforeach
                  </select>
                  <div class="invalid-feedback">The Leave type field is required</div>
                  @error('leavetype')
                  <div class="text-danger"> {{ $message }}</div>
                  @enderror
                </div>
              </div>


              <!-- Gender & DOB -->
              <div class="row col-12 gap">
                <div class="col-md-6">
                  <label for="startdate" class="form-label">Start Date<span class="text-danger">*</span></label>
                  <input type="date" name="startdate" class="form-control" id="startdate" required>
                  <div class="invalid-feedback">The start date field is required</div>
                </div>
                <div class="col-md-6">
                  <label for="enddate" class="form-label">End Date<span class="text-danger">*</span></label>
                  <input type="date" name="enddate" class="form-control" id="enddate" required>
                  <div class="invalid-feedback">The end date field is required</div>
                </div>
              </div>

              <div class="row col-md-12 gap">
                <div class="col-md-6">
                  <label for="reason" class="form-label">Reason<span class="text-danger">*</span></label>
                  <input type="reason" name="reason" class="form-control" id="reason" maxlength="191"
                    placeholder="For what reason you want leave" required>
                  <div class="invalid-feedback">The reason field is required</div>
                </div>
              </div>

              <div class="col-lg-3 col-md-3 col-sm-3">
                <button class="btn btn-primary" type="submit">Apply Leave</button>
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
    const customAlert = document.getElementById('customAlert');
    const btnOk = document.getElementById('btnOk');
    const btnCancel = document.getElementById('btnCancel');
    const form = document.querySelector('form');

    if (customAlert) {
      // Show the alert
      customAlert.classList.remove('hidden');
      btnOk.addEventListener('click', function() {
        // Hide the alert and submit the form
        customAlert.classList.add('hidden');
      });

      btnCancel.addEventListener('click', function() {
        // Hide the alert
        customAlert.style.display = 'none';
      });
    }
    const today = new Date().toISOString().split('T')[0];
    document.getElementById("startdate").setAttribute('min', today);
    document.getElementById("enddate").setAttribute('min', today);

    document.getElementById("startdate").addEventListener("change", function() {
      const startDate = this.value;
      const endDateInput = document.getElementById("enddate");
      endDateInput.setAttribute('min', startDate);
      if (endDateInput.value < startDate) {
        endDateInput.value = startDate;
      }
    });
  });


  setTimeout(function() {
    var message = document.getElementById('alert-success');
    if (message) {
      message.style.display = 'none';
    }
  }, 4000);
</script>
@endsection