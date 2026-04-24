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
                                <h5 class="card-title">Add Attendance</h5>
                                @if (session('success'))
                                <div class="alert alert-success" role="alert" id="alert-success">
                                    {{ session('success') }}
                                </div>
                                @endif
                                @if (session('danger'))
                                <div class="alert alert-danger" role="alert" id="alert-success">
                                    {{ session('danger') }}
                                </div>
                                @endif
                                <form action="putattendancedata" method="post">
                                    @csrf
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Employee</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" name="employee_id" required>
                                                <option value="">Select the Employee</option>
                                                @foreach($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->firstname}}
                                                    {{$employee->lastname}}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('employee_id')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Attendance Status</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <select class="form-select" name="status" required>
                                                    <option value="">Select Status</option>
                                                    <option value="present">Present</option>
                                                    <option value="absent">Absent</option>
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
                                            <div class="input-group date" id="dkk_datetimepicker"
                                                data-target-input="nearest">
                                                <input type="date" class="form-control datetimepicker-input"
                                                    placeholder="Select date" data-target="#dkk_datetimepicker"
                                                    name="date" id="todaydate" value="{{$current_date}}" max="{{$current_date}}" required />

                                            </div>
                                            @error('date')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Time-in</label>
                                        <div class="col-sm-10">
                                            <div class="input-group date" id="kt_datetimepicker_1"
                                                data-target-input="nearest">
                                                <input type="time" class="form-control datetimepicker-input"
                                                    placeholder="Select time in" data-target="#kt_datetimepicker_1"
                                                    name="time_in" id="checkin_time" required
                                                    value="{{ \Carbon\Carbon::parse($current_time)->format('H:i') }}" />

                                            </div>
                                            @error('time_in')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Current Karachi Time: {{ \Carbon\Carbon::now('Asia/Karachi')->format('h:i A') }}</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Time-out</label>
                                        <div class="col-sm-10">
                                            <div class="input-group date" id="kt_datetimepicker_2"
                                                data-target-input="nearest">
                                                <input type="time" class="form-control datetimepicker-input"
                                                    placeholder="Select time out (optional)" data-target="#kt_datetimepicker_2"
                                                    name="time_out" id="checkout_time"
                                                    value="{{ old('time_out', $default_time_out ? \Carbon\Carbon::parse($default_time_out)->format('H:i') : '') }}" />

                                            </div>
                                            @error('time_out')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Optional. Leave empty if not checked out. Must be after time-in when provided.</small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary" value="Submit"
                                                id="submit_button">Save Attendance</button>
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

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Get current Karachi (PKT) time
        function getCurrentKarachiTime() {
            const formatter = new Intl.DateTimeFormat('en-GB', {
                timeZone: 'Asia/Karachi',
                hour: '2-digit',
                minute: '2-digit',
                hour12: false
            });
            const parts = formatter.formatToParts(new Date());
            const hours = parts.find(p => p.type === 'hour').value;
            const minutes = parts.find(p => p.type === 'minute').value;
            return `${hours}:${minutes}`;
        }

        const checkin = document.getElementById('checkin_time');
        const checkout = document.getElementById('checkout_time');
        if (checkin) {
            checkin.value = getCurrentKarachiTime();
            checkin.addEventListener('change', function() {
                const timeIn = this.value;
                if (timeIn) {
                    const [h, m] = timeIn.split(':');
                    let newHours = parseInt(h, 10) + 8;
                    if (newHours < 18) newHours = 18;
                    if (checkout) checkout.value = `${String(newHours).padStart(2, '0')}:${m}`;
                }
            });
            const [h, m] = checkin.value.split(':');
            let outH = parseInt(h, 10) + 8;
            if (outH < 18) outH = 18;
            if (checkout && !checkout.value) checkout.value = `${String(outH).padStart(2, '0')}:${m}`;
        }
    });
</script>
@endsection