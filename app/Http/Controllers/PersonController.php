<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\AttendanceStatus;
use App\Models\Employee;
use Database\Seeders\AttendanceStatusSeeder;

class PersonController extends Controller
{
    public function edit($id)
    {
        $employee = Attendance::findOrFail($id);


        return view('attendance.edit', compact('employee'));
    }

    public function index($id)
    {
        $employee = Attendance::findOrFail($id);
        return view('attendance.edit', compact('employee'));
    }
}
