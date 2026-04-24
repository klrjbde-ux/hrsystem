@extends('master')
@section('css')
@endsection
@section('content')
<main id="main" class="main">
      <div class="container">
        <section class="section register d-flex flex-column align-items-center justify-content-center ">
          <div class="container">
            @if(session('success'))
              <div class="alert alert-success" role="alert">
                {{ session('success') }}
              </div>
            @endif
            <div class="row justify-content-center">
              <div class="col-10 col-md-12 d-flex flex-column align-items-center justify-content-center">
                <div class="col-12 d-flex flex-column align-items-start justify-content-center">
                  <div class="card mb-6" style="width: 100%; max-width: 960px;"> 
                    <div class="card-body">
                      <div class="pt-4 pb-2">
                        <h5 class="card-title text-center pb-0 fs-4">
                          Leave Request from {{$employee->firstname ?? ''}} {{$employee->lastname ?? ''}}
                        </h5>
                      </div>
                      <form class="row g-3 needs-validation" novalidate
                            action="{{ route('aprovereqest.update', $leavereqest->id) }}"
                            method="post">
                        @csrf
                        <!-- Employee Data -->
                        <div class="row col-12" style="margin-top: 20px;">
                          <div class="col-md-6">
                              <label for="leavetype" class="form-label">Leave Type<span class="text-danger">*</span></label>
                              <select class="form-select" name="leavetype" id="leavetype" required>
                                  <option value="">Choose...</option>
                                  @foreach($totalLeaves as $leave)
                                      <option value="{{ $leave->id }}" {{ $leavereqest->leave_type == $leave->id ? 'selected' : '' }}>
                                        {{ $leave->Name }}
                                      </option> 
                                  @endforeach
                              </select>
                              <div class="invalid-feedback">Please, enter Leave Type!</div>
                          </div>
                      </div>
                      <div class="row col-12" style="margin-top: 20px;">
                        <div class="col-md-6">
                              <label for="name" class="form-label">Status<span class="text-danger">*</span></label>
                              <select class="form-select" name="status" id="status" required>
                                    <option value="Pending"  {{ $leavereqest->status == 'Pending'  ? 'selected' : '' }}>Pending</option>
                                    <option value="Approved" {{ $leavereqest->status == 'Approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="Declined" {{ $leavereqest->status == 'Declined' ? 'selected' : '' }}>Declined</option>
                              </select>
                              <div class="invalid-feedback">Pleae! select a Status</div>
                              
                          </div>
                          <div class="col-md-6">
                                <label for="startdate" class="form-label">Start Date<span class="text-danger">*</span></label>
                                <input type="date" name="startdate" class="form-control" id="startdate"  value="{{ $leavereqest->start_date }}" required>
                                <div class="invalid-feedback">Please, enter your Start Date!</div>
                              </div>
                      </div>
                      <div class="row col-12" style="margin-top: 20px;">
                        
                          <div class="col-md-6">
                            <label for="enddate" class="form-label">End Date<span class="text-danger">*</span></label>
                            <input type="date" name="enddate" class="form-control" id="enddate" value="{{ $leavereqest->end_date }}" required>
                            <div class="invalid-feedback">Please, enter your End Date!</div>
                            
                          </div>
                          <div class="col-md-6">
                            <label for="reason" class="form-label">Reason<span class="text-danger">*</span></label>
                            <input type="reason" name="reason" class="form-control" id="reason" value="{{ $leavereqest->reason }}" required>
                            <div class="invalid-feedback">Please, enter Reason of leave!</div>
                          </div>
                      </div>
                      <div class="row pt-20px" style="margin-top: 20px;"> 
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
