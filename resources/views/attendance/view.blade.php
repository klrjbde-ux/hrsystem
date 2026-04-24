@extends('master')

@section('content')
<main id="main" class="main">
<div class="container-fluid py-3">

@if (Session::has('message'))
<div id="successMessage" class="alert {{ Session::get('alert-class','alert-info') }} alert-dismissible fade show">
    {{ Session::get('message') }}
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<section class="section">
<div class="row">
<div class="col-12">

<div class="card">
<div class="card-body pt-4">

<h5 class="card-title">Employees Attendance</h5>

<div class="table-responsive">
<table class="table align-middle text-center">
<thead>
<tr class="text-nowrap">
<th>#</th>
<th>Name</th>
<th>Status</th>
<th>Date</th>
<th>Time In</th>
<th>Time Out</th>
<th>Time Attended</th>
<th>Missing Time</th>
<th>Extra Time</th>
<th>Completed</th>
<th>Action</th>
</tr>
</thead>

<tbody>
@foreach ($employees as $employee)

@php
$timeIn = $employee->first_time_in ? \Carbon\Carbon::parse($employee->first_time_in) : null;
$timeOut = $employee->last_time_out ? \Carbon\Carbon::parse($employee->last_time_out) : null;

$workedMinutes = ($timeIn && $timeOut) ? $timeIn->diffInMinutes($timeOut) : 0;

$officeRequiredMinutes = 0;
if(isset($officeTiming)) {
    $officeStart = \Carbon\Carbon::parse($officeTiming->timing_start);
    $officeEnd   = \Carbon\Carbon::parse($officeTiming->timing_off);
    $officeRequiredMinutes = $officeStart->diffInMinutes($officeEnd);
}

$missingMinutes = max(0, $officeRequiredMinutes - $workedMinutes);
$extraMinutes   = max(0, $workedMinutes - $officeRequiredMinutes);
$isCompleted = $workedMinutes >= $officeRequiredMinutes;

$workedH = floor($workedMinutes / 60);
$workedM = $workedMinutes % 60;

$missingH = floor($missingMinutes / 60);
$missingM = $missingMinutes % 60;

$extraH = floor($extraMinutes / 60);
$extraM = $extraMinutes % 60;
@endphp

<tr class="text-nowrap">

<td>{{ $loop->iteration }}</td>

<td>
{{ ($employee->employee->firstname ?? '') }}
{{ ($employee->employee->lastname ?? '') }}
</td>

<td>
@if(strtolower($employee->status) == 'present')
    <span class="badge bg-success text-white px-3 py-2">Present</span>
@else
    <span class="badge bg-danger text-white px-3 py-2">Absent</span>
@endif
</td>

<td>{{ \Carbon\Carbon::parse($employee->date)->format('d M Y') }}</td>

<td>{{ $timeIn ? $timeIn->format('h:i A') : '-' }}</td>

<td>
@if($timeOut)
    {{ $timeOut->format('h:i A') }}
@else
    Ongoing
@endif
</td>

<td class="text-center">{{ $workedH }}h {{ $workedM }}m</td>

<td class="text-center">{{ $missingH }}h {{ $missingM }}m</td>

<td class="text-center">{{ $extraH }}h {{ $extraM }}m</td>

<td class="text-center">
    @if($isCompleted)
        <span class="badge bg-success text-white">Yes</span>
    @else
        <span class="badge bg-danger text-white">No</span>
    @endif
</td>

<td>
<a href="{{ url('editattendance/'.$employee->id) }}" class="btn btn-sm btn-primary">Edit</a>
<a href="{{ url('delete_attendance/'.$employee->id) }}" class="btn btn-sm btn-danger">Delete</a>
</td>

</tr>
@endforeach

</tbody>
</table>
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
    var alertEl = document.getElementById('successMessage');
    if (alertEl) {
        setTimeout(function() {
            alertEl.style.display = 'none';
        }, 5000);
    }
});
</script>
@endsection