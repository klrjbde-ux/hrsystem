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
                                <h5 class="card-title  text-center">Add Office Timing</h5>
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

                                <!-- Quick Help Card -->
                                <div class="alert alert-info mb-4">
                                    <h6><i class="bi bi-info-circle"></i> Quick Help:</h6>
                                    <ul class="mb-0">
                                        <li>Use <strong>24-hour format</strong> for times</li>
                                        <li><strong>6 PM</strong> = <code>18:00</code> (not 06:00)</li>
                                        <li><strong>9 AM</strong> = <code>09:00</code></li>
                                        <li>Standard 9-hour day: 09:00 to 18:00 = 9 hours total</li>
                                    </ul>
                                </div>

                                <form action="{{ url('addofficetiming') }}" method="post">
                                    @csrf

                                    <input type="hidden" value="{{$id}}" name="id" >

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Work Start Time</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="time" name="entry_time" class="form-control" id="entry_time"
                                                    placeholder="Enter Office Start Time"
                                                    value="{{ $timing_start ? \Carbon\Carbon::parse($timing_start)->format('H:i') : '09:00' }}"
                                                    required>
                                                <div class="input-group-text">
                                                    <i class="bi bi-clock"></i>
                                                </div>
                                            </div>
                                            @error('entry_time')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">
                                                <strong>Enter in 24-hour format:</strong> 9 AM = <code>09:00</code>, 9:30 AM = <code>09:30</code>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Work End Time</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="time" name="exit_time" class="form-control" id="exit_time"
                                                    placeholder="Enter Office End Time"
                                                    value="{{ $timing_off ? \Carbon\Carbon::parse($timing_off)->format('H:i') : '18:00' }}"
                                                    required>
                                                <div class="input-group-text">
                                                    <i class="bi bi-clock-history"></i>
                                                </div>
                                            </div>
                                            @error('exit_time')
                                            <div class="text-danger"> {{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">
                                                <strong>Enter in 24-hour format:</strong> 6 PM = <code>18:00</code>, 5:30 PM = <code>17:30</code>
                                            </small>
                                            <div class="text-danger fw-bold mt-1" id="time_warning" style="display: none;">
                                                ⚠️ Warning: End time seems to be before start time! Did you mean 6 PM (18:00) instead of 6 AM (06:00)?
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Lunch Break Duration</label>
                                        <div class="col-sm-10">
                                            <div class="row">
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Hours</label>
                                                    <select name="break_hours" class="form-select" id="break_hours" required>
                                                        <option value="0" {{ ($break_hours ?? 0) == 0 ? 'selected' : '' }}>0 hours</option>
                                                        <option value="1" {{ ($break_hours ?? 1) == 1 ? 'selected' : '' }}>1 hour</option>
                                                        <option value="2" {{ ($break_hours ?? 0) == 2 ? 'selected' : '' }}>2 hours</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-2">
                                                    <label class="form-label">Minutes</label>
                                                    <select name="break_minutes" class="form-select" id="break_minutes" required>
                                                        <option value="0" {{ ($break_minutes ?? 0) == 0 ? 'selected' : '' }}>0 minutes</option>
                                                        <option value="15" {{ ($break_minutes ?? 0) == 15 ? 'selected' : '' }}>15 minutes</option>
                                                        <option value="30" {{ ($break_minutes ?? 0) == 30 ? 'selected' : '' }}>30 minutes</option>
                                                        <option value="45" {{ ($break_minutes ?? 0) == 45 ? 'selected' : '' }}>45 minutes</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <strong>Total break time:</strong> <span id="total_break_display">1 hour 0 minutes</span>
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Display calculated working hours -->
                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label">Total Working Hours</label>
                                        <div class="col-sm-10">
                                            <div class="input-group">
                                                <input type="text" class="form-control bg-light fw-bold fs-5"
                                                    id="calculated_hours" value="" readonly style="color: #198754;">
                                                <div class="input-group-text">
                                                    <i class="bi bi-calculator"></i>
                                                </div>
                                            </div>
                                            <small class="text-muted">
                                                <strong>Breakdown:</strong>
                                                <span id="time_breakdown">(18:00 - 09:00) - 01:00 = 8 hours</span>
                                            </small>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label class="col-sm-2 col-form-label"></label>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary btn-lg" value="Submit"
                                                id="submit_button">
                                                 Save Office Timing
                                            </button>
                                            <a href="{{ route('officetimingindex') }}" class="btn btn-secondary btn-lg">
                                                Cancel
                                            </a>
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
document.addEventListener('DOMContentLoaded', function() {
    const successMessage = document.getElementById('alert-success');
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.display = 'none';
        }, 4000);
    }

    // Format time to 12-hour AM/PM
    function formatToAMPM(time24) {
        const [hour, minute] = time24.split(':').map(Number);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const hour12 = hour % 12 || 12;
        return `${hour12}:${minute.toString().padStart(2, '0')} ${ampm}`;
    }

    // Calculate total break display
    function updateBreakDisplay() {
        const breakHours = parseInt(document.getElementById('break_hours').value) || 0;
        const breakMinutes = parseInt(document.getElementById('break_minutes').value) || 0;

        let display = '';
        if (breakHours > 0) {
            display += `${breakHours} hour${breakHours !== 1 ? 's' : ''}`;
        }
        if (breakMinutes > 0) {
            if (display) display += ' ';
            display += `${breakMinutes} minute${breakMinutes !== 1 ? 's' : ''}`;
        }
        if (!display) display = '0 hours';

        document.getElementById('total_break_display').textContent = display;
    }

    // Auto-calculate working hours
    function calculateWorkingHours() {
        const entryTime = document.getElementById('entry_time').value;
        const exitTime = document.getElementById('exit_time').value;
        const breakHours = parseInt(document.getElementById('break_hours').value) || 0;
        const breakMinutes = parseInt(document.getElementById('break_minutes').value) || 0;
        const warningDiv = document.getElementById('time_warning');

        if (entryTime && exitTime) {
            // Parse times
            const [entryHour, entryMinute] = entryTime.split(':').map(Number);
            const [exitHour, exitMinute] = exitTime.split(':').map(Number);

            // Show warning if end time seems to be before start time (likely AM/PM confusion)
            if (exitHour < entryHour && exitHour < 12) {
                warningDiv.style.display = 'block';
            } else {
                warningDiv.style.display = 'none';
            }

            // Convert to minutes
            const entryTotalMinutes = entryHour * 60 + entryMinute;
            const exitTotalMinutes = exitHour * 60 + exitMinute;
            const breakTotalMinutes = (breakHours * 60) + breakMinutes;

            // Calculate total minutes between entry and exit
            let totalMinutes = exitTotalMinutes - entryTotalMinutes;

            // Handle overnight shifts (if exit is next day)
            if (totalMinutes < 0) {
                totalMinutes += 24 * 60; // Add 24 hours
            }

            // Create breakdown text
            const totalHours = Math.floor(totalMinutes / 60);
            const totalMinutesRemainder = totalMinutes % 60;
            const breakdown = `(${formatToAMPM(exitTime)} - ${formatToAMPM(entryTime)}) = ${totalHours}h ${totalMinutesRemainder}m total`;
            document.getElementById('time_breakdown').textContent = breakdown;

            // Subtract break time
            const workingMinutes = totalMinutes - breakTotalMinutes;

            if (workingMinutes < 0) {
                document.getElementById('calculated_hours').value = 'ERROR: Break too long!';
                document.getElementById('calculated_hours').classList.add('text-danger');
                document.getElementById('calculated_hours').classList.remove('text-success');
                return;
            }

            // Convert back to hours and minutes
            const workingHours = Math.floor(workingMinutes / 60);
            const workingMinutesRemainder = workingMinutes % 60;

            // Format as readable text
            let formatted = '';
            if (workingHours > 0) {
                formatted += `${workingHours} hour${workingHours !== 1 ? 's' : ''}`;
            }
            if (workingMinutesRemainder > 0) {
                if (formatted) formatted += ' ';
                formatted += `${workingMinutesRemainder} minute${workingMinutesRemainder !== 1 ? 's' : ''}`;
            }

            if (!formatted) formatted = '0 hours';

            document.getElementById('calculated_hours').value = formatted;
            document.getElementById('calculated_hours').classList.remove('text-danger');
            document.getElementById('calculated_hours').classList.add('text-success');
        }
    }

    // Add event listeners
    const inputs = ['entry_time', 'exit_time', 'break_hours', 'break_minutes'];
    inputs.forEach(id => {
        document.getElementById(id).addEventListener('change', function() {
            updateBreakDisplay();
            calculateWorkingHours();
        });
        document.getElementById(id).addEventListener('input', function() {
            updateBreakDisplay();
            calculateWorkingHours();
        });
    });

    // Calculate on page load
    updateBreakDisplay();
    calculateWorkingHours();
});
</script>
@endsection
