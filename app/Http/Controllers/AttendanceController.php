<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use App\Models\OfficeTiming;
use App\Models\EmployeeBreak;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonTimeZone;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function edit($id)
    {
        $employee = Attendance::findOrFail($id);
        return view('attendance.edit', compact('employee'));
    }

    public function rangeindex()
    {
        return view('range');
    }

    public function create()
    {
        $employees = Employee::all();

        // Set Karachi (PKT) timezone
        $karachiTimezone = new CarbonTimeZone('Asia/Karachi');

        $datetime = Carbon::now($karachiTimezone);
        $current_date = $datetime->toDateString();
        $current_time = $datetime->format('H:i:s');

        // Get office timing for auto-calculating time-out
        $officeTiming = OfficeTiming::first();
        if ($officeTiming && !empty($officeTiming->totalworkinghours)) {
            $totalSeconds = $this->parseWorkingHoursToSeconds($officeTiming->totalworkinghours);
            $hours = (int) floor($totalSeconds / 3600);
            $minutes = (int) floor(($totalSeconds % 3600) / 60);

            // Add working hours to current time
            $time_in = Carbon::parse($current_time);
            $default_time_out = $time_in->copy()->addHours($hours)->addMinutes($minutes);

            // Ensure it's not before 6 PM PKT
            $min_time = Carbon::createFromTime(18, 0, 0, $karachiTimezone);
            if ($default_time_out->format('H:i') < '18:00') {
                $default_time_out = $min_time;
            }

            $default_time_out = $default_time_out->format('H:i:s');
        } else {
            $default_time_out = '18:00:00';
        }

        return view('attendance.store', compact('employees', 'current_date', 'current_time', 'default_time_out'));
    }

    public function addattendance()
    {
        $karachiTimezone = new CarbonTimeZone('Asia/Karachi');
        $current_time = Carbon::now($karachiTimezone)->format('H:i:s');
        $today = Carbon::now($karachiTimezone)->toDateString();
        $current_date = Carbon::now($karachiTimezone)->toDateString();

        $employee = Auth::user()?->employee;

        if (!$employee) {
            return view('attendance.addattendanceview', [
                'current_time' => $current_time,
                'entrance' => null,
                'attendance' => null,
                'break' => collect(),
                'extrabreak' => '00:00:00',
                'getbreakstarted' => null,
                'noEmployee' => true,
            ]);
        }

        // Get the latest attendance record
        $entrance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNull('last_time_out')
            ->orderBy('created_at', 'desc')
            ->first();

        // Get attendance record for today
        $attendance = Attendance::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->first();

        // Get breaks for today
        $break = EmployeeBreak::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->get();

        $breaks = EmployeeBreak::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->get();

        $totalBreakTime = $breaks->sum(function ($b) {
            if ($b->break_end_time) {
                return Carbon::parse($b->break_end_time)->diffInSeconds(Carbon::parse($b->break_start_time));
            }
            return 0;
        });

        $extrabreaktaken = $totalBreakTime > 3600 ? ($totalBreakTime - 3600) : 0;
        $extrabreak = gmdate('H:i:s', $extrabreaktaken);

        $getbreakstarted = EmployeeBreak::where('employee_id', $employee->id)
            ->whereDate('date', $today)
            ->whereNull('break_end_time')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('attendance.addattendanceview', compact(
            'current_time',
            'entrance',
            'attendance',
            'break',
            'extrabreak',
            'getbreakstarted'
        ) + ['noEmployee' => false]);
    }

    public function saveAttendanceData(Request $request)
    {
        $employee = Auth::user()?->employee;
        if (!$employee) {
            return redirect()->route('home')->with('danger', 'No employee record is linked to your account. Please contact HR.');
        }
        $employeeId = $employee->id;

        // Set Karachi (PKT) timezone
        $karachiTimezone = new CarbonTimeZone('Asia/Karachi');

        $id = $request->post('id');
        $date = Carbon::now($karachiTimezone)->toDateString();

        if ($id > 0) {
            // Update existing attendance (exit time)
            $atwork = Attendance::find($id);

            if (!$atwork) {
                return redirect()->route('addattendance')->with('danger', 'Attendance record not found');
            }

            $exit_time = Carbon::now($karachiTimezone);
            $atwork->last_time_out = $exit_time;
            $atworktimein = Carbon::parse($atwork->first_time_in);
            $total_time = $exit_time->diffInSeconds($atworktimein);

            // Calculate break time
            $breaks = EmployeeBreak::where('employee_id', $employeeId)
                ->whereDate('date', $date)
                ->get();

            $totalBreakTime = $breaks->sum(function ($break) {
                if ($break->break_end_time) {
                    return Carbon::parse($break->break_end_time)->diffInSeconds(Carbon::parse($break->break_start_time));
                }
                return 0;
            });

            // Calculate actual worked time (after deducting breaks)
            $actualWorkedTime = $total_time - $totalBreakTime;
            $adjustedTotalTime = max($actualWorkedTime, 0);

            // Calculate status based on hours
            $workedHours = $adjustedTotalTime / 3600;
            $status = $this->calculateStatus($workedHours);
            $atwork->status = $status;

            // Save total time (integer seconds; DB column is integer)
            $atwork->total_time = (int) $adjustedTotalTime;

            // Get office timing for delay/extra time calculation
            $officeTiming = OfficeTiming::first();
            if ($officeTiming) {
                $office_total_time_seconds = $this->parseWorkingHoursToSeconds($officeTiming->totalworkinghours);

                // Calculate delay and extra time
                if ($adjustedTotalTime >= $office_total_time_seconds) {
                    // Extra time (worked more than required)
                    $extraTime = $adjustedTotalTime - $office_total_time_seconds;
                    $atwork->is_delay = '00:00:00';
                    $atwork->extra_time = gmdate('H:i:s', $extraTime);
                } else {
                    // Delay (worked less than required)
                    $delayTime = $office_total_time_seconds - $adjustedTotalTime;
                    $atwork->is_delay = gmdate('H:i:s', $delayTime);
                    $atwork->extra_time = '00:00:00';
                }
            }

            $atwork->save();
            return redirect()->route('addattendance')->with('success', 'Attendance updated successfully');
        } else {
            // Create new attendance
            $officeTiming = OfficeTiming::first();

            // Check existing attendance
            $existingAttendance = Attendance::where('employee_id', $employeeId)
                ->whereDate('date', $date)
                ->first();

            if ($existingAttendance) {
                return redirect()->route('addattendance')->with('danger', 'Attendance for today has already been recorded');
            }

            $atworktimein = Carbon::now($karachiTimezone);
            $atwork = new Attendance();
            $atwork->employee_id = $employeeId;
            $atwork->first_time_in = $atworktimein;
            $atwork->date = $date;
            $atwork->status = 'present'; // Initial status
            $atwork->save();

            return redirect()->route('addattendance')->with('success', 'Attendance started successfully');
        }
    }

    public function store(Request $request)
    {
        // Set Karachi (PKT) timezone
        $karachiTimezone = new CarbonTimeZone('Asia/Karachi');

        // Validate the request data
        $validator = Validator::make(
            $request->all(),
            [
                'employee_id' => 'required',
                'time_in' => 'required',
                'time_out' => 'nullable|after:time_in',
                'date' => 'required|date',
                'status' => 'required|in:present,absent'
            ],
            [
                'employee_id.required' => 'Employee field is required',
                'time_in.required' => 'Time in field is required',
                'time_out.after' => 'Time out must be after time in',
                'date.required' => 'Date field is required',
                'date.date' => 'Please provide a valid date',
                'status.required' => 'Please select attendance status',
                'status.in' => 'Status must be Present or Absent'
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Parse times in Karachi (PKT)
        $time_in = Carbon::parse($request->post('time_in'), $karachiTimezone);
        $time_out_value = $request->post('time_out');
        $time_out = $time_out_value ? Carbon::parse($time_out_value, $karachiTimezone) : null;

        // Calculate total time in seconds (0 if no time out)
        $total_time = $time_out ? $time_out->diffInSeconds($time_in) : 0;

        // Check existing attendance
        $existingAttendance = Attendance::where('employee_id', $request->post('employee_id'))
            ->whereDate('date', $request->post('date'))
            ->first();

        if ($existingAttendance) {
            return redirect()->route('create')->with('danger', 'Attendance already exists for this employee on that date');
        }

        // Calculate breaks
        $breaks = EmployeeBreak::where('employee_id', $request->post('employee_id'))
            ->whereDate('date', $request->post('date'))
            ->get();

        $totalBreakTime = $breaks->sum(function ($break) {
            if ($break->break_end_time) {
                return Carbon::parse($break->break_end_time)->diffInSeconds(Carbon::parse($break->break_start_time));
            }
            return 0;
        });

        // Adjust for breaks (only deduct excess beyond 1 hour)
        $actualWorkedTime = $total_time;
        if ($totalBreakTime > 3600) {
            $actualWorkedTime -= ($totalBreakTime - 3600);
        }
        $adjustedTotalTime = max($actualWorkedTime, 0);

        // Use status from form (Present / Absent)
        $finalStatus = $request->post('status');

        // Get office timing
        $officeTiming = OfficeTiming::first();
        if (!$officeTiming) {
            return redirect()->route('create')->with('danger', 'Please add office timing first');
        }

        $office_total_time_seconds = $this->parseWorkingHoursToSeconds($officeTiming->totalworkinghours);

        // Create attendance record
        $attendance = new Attendance();
        $attendance->employee_id = $request->post('employee_id');
        $attendance->first_time_in = $time_in->format('H:i:s');
        $attendance->last_time_out = $time_out ? $time_out->format('H:i:s') : null;
        $attendance->status = $finalStatus;
        $attendance->date = $request->post('date');
        $attendance->total_time = (int) $adjustedTotalTime;

        // Calculate delay/extra time
        if ($adjustedTotalTime >= $office_total_time_seconds) {
            // Extra time (worked more than required)
            $extraTime = $adjustedTotalTime - $office_total_time_seconds;
            $attendance->is_delay = '00:00:00';
            $attendance->extra_time = gmdate('H:i:s', $extraTime);
        } else {
            // Delay (worked less than required)
            $delayTime = $office_total_time_seconds - $adjustedTotalTime;
            $attendance->is_delay = gmdate('H:i:s', $delayTime);
            $attendance->extra_time = '00:00:00';
        }

        $attendance->save();
        return redirect()->route('create')->with('success', 'Attendance Marked Successfully');
    }

    public function view(Request $request)
    {
        $query = Attendance::with('employee')
            ->join('employees', 'attendance.employee_id', '=', 'employees.id')
            ->select('attendance.*');

        // 🔎 Name filter: only first name starts with search
        if ($request->filled('name')) {
            $query->where('employees.firstname', 'like', $request->name . '%');
        }

        // 🔎 Status filter
        if ($request->filled('status')) {
            $query->where('attendance.status', $request->status);
        }

        // 🔎 Date filter
        if ($request->filled('date')) {
            $query->whereDate('attendance.date', $request->date);
        }

        // 🔎 Time In filter
        if ($request->filled('time_in')) {
            $query->whereTime('attendance.first_time_in', $request->time_in);
        }

        // 🔎 Time Out filter
        if ($request->filled('time_out')) {
            $query->whereTime('attendance.last_time_out', $request->time_out);
        }

        $employees = $query
            ->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderBy('attendance.date', 'desc')
            ->get();

        $statuses = AttendanceStatus::all();
        $employeeList = Employee::orderBy('firstname')->orderBy('lastname')->get();

    $officeTiming = \App\Models\OfficeTiming::latest()->first();

    return view('attendance.view', compact(
        'employees',
        'statuses',
        'employeeList',
        'officeTiming'   // ✅ IMPORTANT — added this
    ));
}

    public function range(Request $request)
    {
        $single_date = $request->filled('single_date') ? $request->single_date : null;

        if ($single_date) {
            $validator = Validator::make(
                $request->all(),
                [
                    'single_date' => 'required|date',
                ],
                [
                    'single_date.required' => 'Single date field is required',
                    'single_date.date' => 'Please provide a valid date',
                ]
            );
            $start_date = $single_date;
            $end_date = $single_date;
        } else {
            $validator = Validator::make(
                $request->all(),
                [
                    'date_timepicker_start' => 'required|date',
                    'date_timepicker_end' => 'required|date|after_or_equal:date_timepicker_start',
                ],
                [
                    'date_timepicker_start.required' => 'Start date field is required',
                    'date_timepicker_end.required' => 'End date field is required',
                    'date_timepicker_end.after_or_equal' => 'End date must be on or after Start date',
                ]
            );
            $start_date = $request->date_timepicker_start;
            $end_date = $request->date_timepicker_end;
        }

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $employee_id = $request->employee_id;

        $query = Attendance::where('attendance.date', '>=', $start_date)
            ->where('attendance.date', '<=', $end_date)
            ->with('employee')
            ->join('employees', 'attendance.employee_id', '=', 'employees.id')
            ->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderBy('attendance.date', 'desc')
            ->select('attendance.*');

        if (!empty($employee_id)) {
            $query->where('attendance.employee_id', $employee_id);
        }

        $employees = $query->get();
        $statuses = AttendanceStatus::all();
        $employeeList = Employee::orderBy('firstname')->orderBy('lastname')->get();

        $inputForRedirect = $request->only('date_timepicker_start', 'date_timepicker_end', 'employee_id', 'single_date');

        if ($employees->count() > 0) {
            return view('attendance.view', compact('employees', 'statuses', 'employeeList'))->withInput($inputForRedirect);
        } else {
            return redirect()->route('view')->with('message', 'No Record Found')->withInput($inputForRedirect);
        }
    }

    public function editable()
    {
        $attendances = Attendance::with('employee')
            ->orderBy('date', 'desc')
            ->limit(200)
            ->get();
        $statuses = AttendanceStatus::all();

        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();

        return view('attendance.editable', compact('attendances', 'employees'));
    }

    public function editableSave(Request $request)
    {
        $rows = $request->input('rows', []);
        if (!is_array($rows)) {
            return response()->json(['message' => 'Invalid payload.'], 422);
        }

        foreach ($rows as $idx => $row) {
            $isUpdate = !empty($row['id']);

            $validator = Validator::make(
                $row,
                [
                    'id' => 'nullable|integer|exists:attendance,id',
                    'employee_id' => 'required|integer|exists:employees,id',
                    'status' => 'required|in:present,absent',
                    // New row: future date not allowed. Existing row: allow save even if old data has future date.
                    'date' => $isUpdate ? 'required|date' : ('required|date|before_or_equal:' . Carbon::today()->toDateString()),
                    'time_in' => 'required|date_format:H:i',
                    'time_out' => 'nullable|date_format:H:i|after:time_in',
                ],
                [
                    'time_out.after' => 'Check-out must be after Check-in',
                ]
            );

            if ($validator->fails()) {
                return response()->json([
                    'message' => 'Row ' . ($idx + 1) . ': ' . $validator->errors()->first(),
                ], 422);
            }

            $timeIn = $row['time_in'] . ':00';
            $timeOut = !empty($row['time_out']) ? ($row['time_out'] . ':00') : null;

            // Calculate total_time in seconds (0 if no checkout)
            $totalTimeSeconds = 0;
            if ($timeOut) {
                $totalTimeSeconds = Carbon::parse($timeOut)->diffInSeconds(Carbon::parse($timeIn));
            }

            if (!empty($row['id'])) {
                $attendance = Attendance::findOrFail($row['id']);
                $attendance->employee_id = $row['employee_id'];
                $attendance->status = $row['status'];
                $attendance->date = $row['date'];
                $attendance->first_time_in = $timeIn;
                $attendance->last_time_out = $timeOut;
                $attendance->total_time = (int) $totalTimeSeconds;
                // keep safe defaults if columns are NOT NULL in DB
                $attendance->is_delay = $attendance->is_delay ?? '00:00:00';
                $attendance->extra_time = $attendance->extra_time ?? '00:00:00';
                $attendance->save();
            } else {
                // prevent duplicate attendance for employee/date
                $existing = Attendance::where('employee_id', $row['employee_id'])
                    ->whereDate('date', $row['date'])
                    ->first();
                if ($existing) {
                    return response()->json([
                        'message' => 'Row ' . ($idx + 1) . ': Attendance already exists for this employee on this date.',
                    ], 422);
                }

                Attendance::create([
                    'employee_id' => $row['employee_id'],
                    'status' => $row['status'],
                    'date' => $row['date'],
                    'first_time_in' => $timeIn,
                    'last_time_out' => $timeOut,
                    'total_time' => (int) $totalTimeSeconds,
                    'is_delay' => '00:00:00',
                    'extra_time' => '00:00:00',
                ]);
            }
        }

        return response()->json([
            'message' => 'Attendance saved successfully.',
            'refresh' => true,
        ]);
    }

    public function editableDelete($id)
    {
        $attendance = Attendance::find($id);
        if (!$attendance) {
            return response()->json(['message' => 'Record not found.'], 404);
        }

        $attendance->delete();

        return response()->json(['message' => 'Row deleted successfully.']);
    }

    public function delete($id)
    {
        $employee = Attendance::find($id);
        $employee->delete();
        return redirect()->route("view")->with("message", "Deleted Successfully");
    }

    public function update(Request $request, $id)
    {
        // Set Karachi (PKT) timezone
        $karachiTimezone = new CarbonTimeZone('Asia/Karachi');

        $validator = Validator::make(
            $request->all(),
            [
                'thecheckintime' => 'required',
                'thecheckouttime' => 'required|after:thecheckintime',
                'thedatetoday' => 'required|date',
                'status' => 'required|in:present,absent',
            ],
            [
                'thecheckintime.required' => 'Check in time field is required',
                'thecheckouttime.required' => 'Check out time field is required',
                'thecheckouttime.after' => 'Check out must be after check in',
                'thedatetoday.required' => 'Date is required',
                'thedatetoday.date' => 'Please provide a valid date',
                'status.required' => 'Please select attendance status',
                'status.in' => 'Status must be Present or Absent',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Parse check-in and check-out times
        $checkin = Carbon::parse($request->post('thecheckintime'), $karachiTimezone);
        $checkout = Carbon::parse($request->post('thecheckouttime'), $karachiTimezone);

        // Calculate total time
        $total_time = $checkout->diffInSeconds($checkin);

        $employees = Attendance::findOrFail($id);
        $date = $request->thedatetoday;

        // Calculate breaks
        $breaks = EmployeeBreak::where('employee_id', $request->empid)
            ->whereDate('date', $date)
            ->get();

        $totalBreakTime = $breaks->sum(function ($break) {
            if ($break->break_end_time) {
                return Carbon::parse($break->break_end_time)->diffInSeconds(Carbon::parse($break->break_start_time));
            }
            return 0;
        });

        // Adjust for breaks (only deduct excess beyond 1 hour)
        $actualWorkedTime = $total_time;
        if ($totalBreakTime > 3600) {
            $actualWorkedTime -= ($totalBreakTime - 3600);
        }
        $adjustedTotalTime = max($actualWorkedTime, 0);

        // Use status from form (Present / Absent)
        $finalStatus = $request->post('status');

        // Get office timing
        $officeTiming = OfficeTiming::first();
        if (!$officeTiming) {
            return redirect()->route('view')->with('danger', 'Please add office timing');
        }

        $office_total_time_seconds = $this->parseWorkingHoursToSeconds($officeTiming->totalworkinghours);

        // Update attendance
        $employees->employee_id = $request->empid;
        $employees->first_time_in = $checkin->format('H:i:s');
        $employees->last_time_out = $checkout->format('H:i:s');
        $employees->status = $finalStatus;
        $employees->date = $request->thedatetoday;
        $employees->total_time = (int) $adjustedTotalTime;

        // Calculate delay/extra time
        if ($adjustedTotalTime >= $office_total_time_seconds) {
            // Extra time (worked more than required)
            $extraTime = $adjustedTotalTime - $office_total_time_seconds;
            $employees->is_delay = '00:00:00';
            $employees->extra_time = gmdate('H:i:s', $extraTime);
        } else {
            // Delay (worked less than required)
            $delayTime = $office_total_time_seconds - $adjustedTotalTime;
            $employees->is_delay = gmdate('H:i:s', $delayTime);
            $employees->extra_time = '00:00:00';
        }

        $employees->update();

        return redirect('/viewattendance')->with("message", "Updated Successfully");
    }

    /**
     * Parse totalworkinghours string to seconds. Handles null, empty, HH:mm, HH:mm:ss.
     *
     * @param string|null $workingHoursStr e.g. "08:00:00" or "08:00"
     * @return int Total seconds
     */
    private function parseWorkingHoursToSeconds(?string $workingHoursStr): int
    {
        if (empty($workingHoursStr) || !is_string($workingHoursStr)) {
            return 8 * 3600; // default 8 hours
        }
        $parts = explode(':', trim($workingHoursStr));
        $h = (int) ($parts[0] ?? 0);
        $m = (int) ($parts[1] ?? 0);
        $s = (int) ($parts[2] ?? 0);
        return ($h * 3600) + ($m * 60) + $s;
    }

    /**
     * Calculate attendance status based on hours worked
     *
     * @param float $workedHours Hours worked
     * @return string Status text
     */
    private function calculateStatus($workedHours)
    {
        if ($workedHours >= 9) {
            return 'present';
        } elseif ($workedHours >= 5 && $workedHours < 9) {
            return 'late';
        } elseif ($workedHours >= 1 && $workedHours < 5) {
            return 'short leave';
        } else {
            return 'absent';
        }
    }
}
