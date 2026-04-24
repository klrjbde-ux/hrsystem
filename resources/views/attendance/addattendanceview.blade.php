@extends('master')
@section('content')
@section('css')
@endsection
<main id="main" class="main">
    <div class="container-fluid">
        <section class="section d-flex flex-column align-items-center justify-content-center py-4 center">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card mb-5">
                            <div class="card-body">
                                <div class="row">
                                    @if (session('success'))
                                    <div class="alert alert-success" role="alert" id="alert-success">
                                        {{ session('success') }}
                                    </div>
                                    @endif
                                    @if (session('danger'))
                                    <div class="alert alert-danger" role="alert" id="alert-danger">
                                        {{ session('danger') }}
                                    </div>
                                    @endif
                                    @if (!empty($noEmployee))
                                    <div class="alert alert-warning w-100" role="alert">
                                        No employee record is linked to your account. Please contact HR to use Add Attendance / Break.
                                    </div>
                                    @endif

                                    @if (empty($noEmployee))
                                    <!-- Add Attendance Card -->
                                    <div class="col-md-6 mb-3 d-flex">
                                        <div class="card text-center flex-fill">
                                            <div class="card-header">
                                                Add Attendance
                                            </div>
                                            <div class="card-body">
                                                <form action="saveattendancedata" method="post">
                                                    @csrf
                                                    <input type="hidden" name="entrance" class="form-control" id="current_time" value="{{ $current_time }}" readonly />
                                                    @if ($entrance)
                                                    <input type="hidden" value="{{ $entrance->id }}" name="id">
                                                    <button type="submit" class="btn btn-primary">Exit Office</button>
                                                    @else
                                                    <button type="submit" class="btn btn-primary">Add Attendance</button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Manage Breaks Card -->
                                    <div class="col-md-6 mb-3 d-flex">
                                        <div class="card text-center flex-fill">
                                            <div class="card-header">
                                                Add Break
                                            </div>
                                            <div class="card-body">
                                                <form action="{{ url('addemployeestartbreak') }}" method="post">
                                                    @csrf
                                                    <input type="hidden" name="break_start" class="" id="current_time" value="{{ $current_time }}" />
                                                    @if ($getbreakstarted)
                                                    <input type="hidden" value="{{ $getbreakstarted->id }}" name="id">
                                                    <button type="submit" class="btn btn-primary">Break End</button>
                                                    @else
                                                    <button type="submit" class="btn btn-primary">Break Start</button>
                                                    @endif
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="row">
                <div class="col-lg-12" style="margin-left: 20px">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-header">
                                Attendance Detail
                            </div>
                            <table class="table table-responsive-lg table-responsive-sm table-responsive-md">
                                <thead>
                                    @if ($attendance)
                                    <tr>
                                        <th scope="col" style="font-weight: 500">Office Entrance time:</th>
                                        <th scope="col" style="font-weight: 500">Office Exit time:</th>
                                        <th style="font-weight: 500">Total working Hours</th>
                                        <th style="font-weight: 500">Delay</th>
                                        <th style="font-weight: 500">Extra Time</th>
                                    </tr>
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($attendance->first_time_in)->format('h:i A') }}</td>
                                        <td>
                                            @if ($attendance->last_time_out)
                                            {{ \Carbon\Carbon::parse($attendance->last_time_out)->format('h:i A') }}
                                            @else
                                            <span class="badge rounded-pill bg-success">Ongoing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attendance->total_time)
                                            {{ gmdate('H:i:s', (int) $attendance->total_time) }}
                                            @else
                                            <span class="badge rounded-pill bg-success">Ongoing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attendance->is_delay)
                                            {{ $attendance->is_delay }}
                                            @else
                                            00:00:00
                                            @endif
                                        </td>
                                        <td>
                                            @if ($attendance->extra_time)
                                            {{ $attendance->extra_time }}
                                            @else
                                            00:00:00
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

                                    @if ($break->isNotEmpty())
                                    <tr>
                                        <th style="font-size: 18px;font-weight:500"> Break Detail</th>
                                    </tr>
                                    <tr>
                                        <th scope="col" style="font-weight: 500"># &nbsp;&nbsp;Break Start time</th>
                                        <th scope="col" style="font-weight: 500">Break End time</th>
                                        <th scope="col" style="font-weight: 500">Total Break time</th>
                                        <th></th>
                                        <th></th>
                                    </tr>

                                    @foreach ($break as $breaks)
                                    <tr>
                                        <td>{{ $loop->iteration }} &nbsp;&nbsp;{{ \Carbon\Carbon::parse($breaks->break_start_time)->format('h:i A') }}</td>
                                        <td>
                                            @if ($breaks->break_end_time)
                                            {{ \Carbon\Carbon::parse($breaks->break_end_time)->format('h:i A') }}
                                            @else
                                            <span class="badge rounded-pill bg-success">Ongoing</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($breaks->total_time)
                                            {{ $breaks->total_time }}
                                            @else
                                            <span class="badge rounded-pill bg-success">Ongoing</span>
                                            @endif
                                        </td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</main>