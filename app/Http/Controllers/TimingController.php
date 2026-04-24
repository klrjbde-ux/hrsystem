<?php

namespace App\Http\Controllers;

use App\Models\OfficeTiming;
use App\Models\EmployeeBreak;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TimingController extends Controller
{
    // Show all office timings
    public function officetimingindex()
    {
        $OfficeTiming = OfficeTiming::all();
        return view('attendance.employeeofficetimingindex', compact('OfficeTiming'));
    }

    // Show create/edit form
    public function officetiming(Request $request, $id = '')
    {
        if ($id > 0) {
            $timing = OfficeTiming::find($id);
            $result['id'] = $timing->id;
            $result['timing_start'] = $timing->timing_start;
            $result['timing_off'] = $timing->timing_off;
            $result['break'] = $timing->break;

            // Parse break time for dropdowns
            $breakTime = explode(':', $timing->break);
            $result['break_hours'] = intval($breakTime[0] ?? 0);
            $result['break_minutes'] = intval($breakTime[1] ?? 0);
        } else {
            $result['id'] = 0;
            $result['timing_start'] = '09:00:00';
            $result['timing_off'] = '18:00:00';
            $result['break'] = '01:00:00';
            $result['break_hours'] = 1;
            $result['break_minutes'] = 0;
        }

        return view('attendance.employeeofficetiming', [
            'id' => $result['id'],
            'timing_start' => $result['timing_start'],
            'timing_off' => $result['timing_off'],
            'break' => $result['break'],
            'break_hours' => $result['break_hours'],
            'break_minutes' => $result['break_minutes'],
        ]);
    }

    // Store or update office timing
    public function addofficetiming(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'entry_time' => 'required',
            'exit_time' => 'required',
            'break_hours' => 'required|integer|min:0',
            'break_minutes' => 'required|integer|min:0|max:59',
        ], [
            'entry_time.required' => 'Start time field is required',
            'exit_time.required' => 'End time field is required',
            'break_hours.required' => 'Break hours field is required',
            'break_minutes.required' => 'Break minutes field is required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // MANUAL CALCULATION - SIMPLE AND RELIABLE
        $entry_time = $request->entry_time; // "09:00"
        $exit_time = $request->exit_time;   // "18:00"

        list($entry_hour, $entry_minute) = explode(':', $entry_time);
        list($exit_hour, $exit_minute) = explode(':', $exit_time);

        $entry_hour = intval($entry_hour);
        $entry_minute = intval($entry_minute);
        $exit_hour = intval($exit_hour);
        $exit_minute = intval($exit_minute);

        // Convert to minutes since midnight
        $entry_total_minutes = ($entry_hour * 60) + $entry_minute;  // 9:00 = 540
        $exit_total_minutes = ($exit_hour * 60) + $exit_minute;     // 18:00 = 1080

        // Calculate work duration in minutes
        $total_work_minutes = $exit_total_minutes - $entry_total_minutes; // 1080 - 540 = 540

        // If result is negative, add 24 hours (overnight shift)
        if ($total_work_minutes < 0) {
            $total_work_minutes += 1440; // 24 * 60
        }

        // Break time calculation
        $break_hours = intval($request->break_hours);
        $break_minutes = intval($request->break_minutes);
        $break_total_minutes = ($break_hours * 60) + $break_minutes;

        // Verify break doesn't exceed work time
        if ($break_total_minutes > $total_work_minutes) {
            return redirect()->back()->with(
                'danger',
                "Break time cannot exceed total work time."
            )->withInput();
        }

        // Calculate working minutes after break
        $working_minutes = $total_work_minutes - $break_total_minutes; // 540 - 60 = 480

        // Convert to hours and minutes
        $working_hours = floor($working_minutes / 60);  // 8
        $working_minutes_remainder = $working_minutes % 60; // 0

        // Determine if update or new
        $id = $request->post('id');

        if ($id > 0) {
            $officetiming = OfficeTiming::find($id);
            if (!$officetiming) {
                return redirect()->back()->with('danger', 'Office timing record not found.');
            }
        } else {
            if (OfficeTiming::count() > 0) {
                return redirect()->back()->with('danger', 'Office timing already exists. Please edit the existing one.');
            }
            $officetiming = new OfficeTiming();
        }

        // Save to database
        $officetiming->timing_start = sprintf('%02d:%02d:00', $entry_hour, $entry_minute);
        $officetiming->timing_off = sprintf('%02d:%02d:00', $exit_hour, $exit_minute);
        $officetiming->break = sprintf('%02d:%02d:00', $break_hours, $break_minutes);
        $officetiming->totalworkinghours = sprintf('%02d:%02d:00', $working_hours, $working_minutes_remainder);
        $officetiming->save();

        $msg = $id > 0 ? 'Office timing updated successfully' : 'Office timing added successfully';
        return redirect()->route('officetimingindex')->with('success', $msg);
    }

    // Delete office timing
    public function deleteofficetiming($id)
    {
        $model = OfficeTiming::findOrFail($id);
        $model->delete();
        return redirect()->route('officetimingindex')->with('success', 'Office timing deleted successfully');
    }

    // Show employee break page
    public function break()
    {
        $current_time = now()->format('h:i A');
        $today = now()->toDateString();

        $getbreakstarted = EmployeeBreak::where('employee_id', Auth::user()->employee->id)
            ->whereDate('date', $today)
            ->whereNull('break_end_time')
            ->orderBy('created_at', 'desc')
            ->first();

        return view('attendance.employeebreak', compact('current_time', 'getbreakstarted'));
    }

    // Start or end employee break
    public function addemployeestartbreak(Request $request)
    {
        $id = $request->post('id');
        $today = Carbon::today()->toDateString();

        if ($id > 0) {
            // End break
            $breakstarted = EmployeeBreak::find($id);
            $break_end_time = Carbon::now();
            $breakstarted->break_end_time = $break_end_time;

            // Calculate total break time
            $break_start_time = Carbon::parse($breakstarted->break_start_time);
            $total_time = $break_end_time->diffInSeconds($break_start_time);
            $breakstarted->total_time = gmdate('H:i:s', $total_time);
            $breakstarted->save();

            return redirect()->route('addattendance')->with('success', 'Break Ended');
        } else {
            // Start break
            $breakstart = new EmployeeBreak();
            $breakstart->employee_id = Auth::user()->employee->id;
            $breakstart->break_start_time = Carbon::now();
            $breakstart->date = $today;
            $breakstart->save();

            return redirect()->route('addattendance')->with('success', 'Break Started');
        }
    }
}
