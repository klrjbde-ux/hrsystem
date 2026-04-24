@extends('master')
@section('content')
@section('css')
@endsection
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">

                        <div class="card mb-5" style="width: 100%;">
                            <div class="card-body">
                                <h5 class="card-title">Edit Attendance</h5>
                                <form action="{{url('update_attendance/'.$employee->id)}}" method="post" id="my-edit-form">
                                    @csrf

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Name</label>
                                        <input type="hidden" name="empid" value="{{$employee->employee->id ?? ''}}">
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nameemp" name="nameofemployee" value="{{$employee->employee->firstname}} {{$employee->employee->lastname}}" readonly disabled>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Status</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-select" name="status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="present" {{ old('status', $employee->status) == 'present' ? 'selected' : '' }}>Present</option>
                                                    <option value="absent" {{ old('status', $employee->status) == 'absent' ? 'selected' : '' }}>Absent</option>
                                                </select>

                                            </div>
                                            @error('status')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Date</label>
                                        <div class="col-sm-10">
                                            <div class="input-group date" id="date" data-target-input="nearest">
                                                <input type="date" class="form-control datetimepicker-input" placeholder="Select date"
                                                    data-target="#date" name="thedatetoday" id="thedate"
                                                    value="{{ old('thedatetoday', \Carbon\Carbon::parse($employee->date)->format('Y-m-d')) }}"
                                                    max="{{ \Carbon\Carbon::today()->format('Y-m-d') }}" required />

                                            </div>
                                            @error('thedatetoday')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Time after 6 PM counts as extra hours</small>

                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Check-in</label>
                                        <div class="col-sm-10">
                                            <div class="input-group date" id="checkin" data-target-input="nearest">
                                                <input type="time" class="form-control" placeholder="Select check-in time"
                                                    data-target="#checkin" name="thecheckintime"
                                                    value="{{ \Carbon\Carbon::parse($employee->first_time_in)->format('H:i') }}"
                                                    required />

                                            </div>
                                            @error('thecheckintime')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Check-out</label>
                                        <div class="col-sm-10">
                                            <div class="input-group date" id="checkout" data-target-input="nearest">
                                                <input type="time" class="form-control" placeholder="Select check-out time"
                                                    data-target="#checkout" name="thecheckouttime"
                                                    value="{{ \Carbon\Carbon::parse($employee->last_time_out)->format('H:i') }}"
                                                    required />

                                            </div>
                                            @error('thecheckouttime')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Time after 6 PM counts as extra hours</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary" value="Submit">Update</button>
                                            <a href="{{ url()->previous() }}"><button type="button" class="btn btn-danger" id="btnback">Back</button></a>
                                        </div>
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
@endsection