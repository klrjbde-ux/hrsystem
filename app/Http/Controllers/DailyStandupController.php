<?php

namespace App\Http\Controllers;

use App\Models\DailyStandupMeeting;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DailyStandupController extends Controller
{
    /**
     * Show meeting list (Date | Emp Name | Status | Remarks).
     */
    public function index(Request $request)
    {
        $query = DailyStandupMeeting::with('employee')
            ->join('employees', 'daily_standup_meetings.employee_id', '=', 'employees.id')
            ->select('daily_standup_meetings.*');

        // ✅ Date filter
        if ($request->filled('date')) {
            $query->whereDate('daily_standup_meetings.date', $request->date);
        }

        // ✅ Status filter
        if ($request->filled('status')) {
            $query->where('daily_standup_meetings.status', $request->status);
        }

        // ✅ Employee first name filter only (starts with)
        if ($request->filled('employee')) {
            $query->where('employees.firstname', 'like', $request->employee . '%');
        }

        $meetings = $query
            ->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderBy('daily_standup_meetings.date', 'desc')
            ->get();

        return view('dailystandup.index', compact('meetings'));
    }

    /**
     * Show form to add emp meeting.
     */
    public function create()
    {
        $employees = Employee::orderBy('firstname')->get();

        return view('dailystandup.create', compact('employees'));
    }

    /**
     * Store new emp meeting.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'date' => 'required|date',
                'employee_id' => 'required|exists:employees,id',
                'status' => 'required|in:present,absent',
                'remarks' => 'nullable|string',
            ],
            [
                'date.required' => 'Date is required.',
                'date.date' => 'Please provide a valid date.',
                'employee_id.required' => 'Please select an employee.',
                'employee_id.exists' => 'Selected employee is invalid.',
                'status.required' => 'Please select status.',
                'status.in' => 'Status must be Present or Absent.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DailyStandupMeeting::create([
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('dailystandup.index')->with('success', 'Meeting added successfully.');
    }

    /**
     * Show form to edit meeting.
     */
    public function edit($id)
    {
        $meeting = DailyStandupMeeting::findOrFail($id);
        $employees = Employee::orderBy('firstname')->get();

        return view('dailystandup.edit', compact('meeting', 'employees'));
    }

    /**
     * Update meeting.
     */
    public function update(Request $request, $id)
    {
        $meeting = DailyStandupMeeting::findOrFail($id);

        $validator = Validator::make(
            $request->all(),
            [
                'date' => 'required|date',
                'employee_id' => 'required|exists:employees,id',
                'status' => 'required|in:present,absent',
                'remarks' => 'nullable|string',
            ],
            [
                'date.required' => 'Date is required.',
                'date.date' => 'Please provide a valid date.',
                'employee_id.required' => 'Please select an employee.',
                'employee_id.exists' => 'Selected employee is invalid.',
                'status.required' => 'Please select status.',
                'status.in' => 'Status must be Present or Absent.',
            ]
        );

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $meeting->update([
            'date' => $request->date,
            'employee_id' => $request->employee_id,
            'status' => $request->status,
            'remarks' => $request->remarks,
        ]);

        return redirect()->route('dailystandup.index')->with('success', 'Meeting updated successfully.');
    }

    /**
     * Delete meeting.
     */
    public function destroy($id)
    {
        $meeting = DailyStandupMeeting::findOrFail($id);
        $meeting->delete();

        return redirect()->route('dailystandup.index')->with('success', 'Meeting deleted successfully.');
    }

    // ========== Editable meeting list (AJAX / DataTables) – separate from index ==========

    /**
     * Show editable meeting list page (DataTables + AJAX, update/delete via popup).
     */
    public function manage()
    {
        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();

        return view('dailystandup.manage', compact('employees'));
    }

    /**
     * Return meeting list as JSON for DataTables (AJAX).
     */
    public function dataList(Request $request)
    {
        $meetings = DailyStandupMeeting::with('employee')
            ->join('employees', 'daily_standup_meetings.employee_id', '=', 'employees.id')
            ->orderBy('daily_standup_meetings.date', 'desc')
            ->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->select('daily_standup_meetings.*')
            ->get();

        $data = [];
        foreach ($meetings as $index => $meeting) {
            $data[] = [
                'id'         => $meeting->id,
                'row_no'     => $index + 1,
                'date'       => $meeting->date ? $meeting->date->format('Y-m-d') : '',
                'date_display' => $meeting->date ? $meeting->date->format('d M Y') : '—',
                'employee_id' => $meeting->employee_id,
                'employee_name' => trim(($meeting->employee->firstname ?? '') . ' ' . ($meeting->employee->lastname ?? '')),
                'status'     => $meeting->status ?? '—',
                'remarks'    => $meeting->remarks ?? '—',
            ];
        }

        return response()->json(['data' => $data]);
    }

    /**
     * Update meeting via AJAX (for editable list popup).
     */
   public function updateAjax(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'id'          => 'required|exists:daily_standup_meetings,id',
            'date'        => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'status'      => 'required|in:present,absent',
            'remarks'     => 'nullable|string',
        ],
        [
            'id.required' => 'Meeting ID is required.',
            'id.exists' => 'Meeting not found in database.',
            'date.required' => 'Date is required.',
            'date.date' => 'Please provide a valid date.',
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'status.required' => 'Please select status.',
            'status.in' => 'Status must be Present or Absent.',
        ]
    );

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    try {
        $meeting = DailyStandupMeeting::findOrFail($request->id);
        $meeting->update([
            'date'        => $request->date,
            'employee_id' => $request->employee_id,
            'status'      => $request->status,
            'remarks'     => $request->remarks,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meeting updated successfully.',
            'meeting' => $meeting
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error updating meeting: ' . $e->getMessage()
        ], 500);
    }
}

   public function storeAjax(Request $request)
{
    $validator = Validator::make(
        $request->all(),
        [
            'date' => 'required|date',
            'employee_id' => 'required|exists:employees,id',
            'status' => 'required|in:present,absent',
            'remarks' => 'nullable|string',
        ],
        [
            'date.required' => 'Date is required.',
            'date.date' => 'Please provide a valid date.',
            'employee_id.required' => 'Please select an employee.',
            'employee_id.exists' => 'Selected employee is invalid.',
            'status.required' => 'Please select status.',
            'status.in' => 'Status must be Present or Absent.',
        ]
    );

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
    }

    $meeting = DailyStandupMeeting::create([
        'date' => $request->date,
        'employee_id' => $request->employee_id,
        'status' => $request->status,
        'remarks' => $request->remarks,
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Meeting added successfully.',
        'meeting' => [
            'id' => $meeting->id,
            'date' => $meeting->date,
            'employee_id' => $meeting->employee_id,
            'status' => $meeting->status,
            'remarks' => $meeting->remarks
        ]
    ]);
}
    /**
     * Delete meeting via AJAX (for editable list popup).
     */
    public function destroyAjax(Request $request)
    {
        $id = $request->input('id');
        if (!$id) {
            return response()->json(['success' => false, 'message' => 'Meeting ID is required.'], 422);
        }
        $meeting = DailyStandupMeeting::find($id);
        if (!$meeting) {
            return response()->json(['success' => false, 'message' => 'Meeting not found.'], 404);
        }
        $meeting->delete();

        return response()->json(['success' => true, 'message' => 'Meeting deleted successfully.']);
    }
}
