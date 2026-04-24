<?php

namespace App\Http\Controllers;

use App\Models\EmployeeInterview;
use Illuminate\Http\Request;

class EmployeeInterviewController extends Controller
{
    // INDEX
    public function index(Request $request)
    {
        $query = EmployeeInterview::query();

        // Filter by candidate name (starts with, case-insensitive)
        if ($request->filled('name')) {
            $name = trim($request->name);
            $query->where('candidate_name', 'like', $name . '%');
            // If using MySQL and want case-insensitive search:
            // $query->whereRaw('LOWER(candidate_name) like ?', [strtolower($name) . '%']);
        }

        // Filter by interview status
        if ($request->filled('status')) {
            $query->where('interview_status', $request->status);
        }

        // Filter by interview date
        if ($request->filled('interview_date')) {
            $query->whereDate('interview_date', $request->interview_date);
        }

        $interviews = $query->latest()->get();

        return view('employeeinterviews.index', compact('interviews'));
    }
    // CREATE
    public function create()
    {
        return view('employeeinterviews.create');
    }

    // STORE
    public function store(Request $request)
    {
        $request->validate([
            'candidate_name'    => 'required|string',
            'applied_for_job'   => 'required|string',
            'cv'                => 'nullable|file',
            'interview_date'    => 'required|date',
            'current_salary'    => 'required|numeric',
            'expected_salary'   => 'required|numeric',
            'date_of_joining'   => 'required|date',
            'interview_status'  => 'required|string',
            'interview_remarks' => 'required|string',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        EmployeeInterview::create([
            'candidate_name'    => $request->candidate_name,
            'applied_for_job'   => $request->applied_for_job,
            'cv'                => $cvPath,
            'interview_date'    => $request->interview_date,
            'current_salary'    => $request->current_salary,
            'expected_salary'   => $request->expected_salary,
            'date_of_joining'   => $request->date_of_joining,
            'interview_status'  => $request->interview_status,
            'interview_remarks' => $request->interview_remarks,
        ]);

        return redirect()->route('employeeinterviews.index')
            ->with('success', 'Interview added successfully');
    }

    // EDIT
    public function edit($id)
    {
        $interview = EmployeeInterview::findOrFail($id);
        return view('employeeinterviews.edit', compact('interview'));
    }

    // UPDATE
    public function update(Request $request, $id)
    {
        $interview = EmployeeInterview::findOrFail($id);

        $request->validate([
            'candidate_name'    => 'required|string',
            'applied_for_job'   => 'required|string',
            'cv'                => 'nullable|file',
            'interview_date'    => 'required|date',
            'current_salary'    => 'required|numeric',
            'expected_salary'   => 'required|numeric',
            'date_of_joining'   => 'required|date',
            'interview_status'  => 'required|string',
            'interview_remarks' => 'required|string',
        ]);

        if ($request->hasFile('cv')) {
            $interview->cv = $request->file('cv')->store('cvs', 'public');
        }

        $interview->update($request->except('cv'));

        return redirect()->route('employeeinterviews.index')
            ->with('success', 'Interview updated successfully');
    }

    // DELETE
    public function destroy($id)
    {
        EmployeeInterview::findOrFail($id)->delete();

        return redirect()->route('employeeinterviews.index')
            ->with('success', 'Interview deleted successfully');
    }
}
