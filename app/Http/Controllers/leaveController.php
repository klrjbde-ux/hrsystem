<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\leaves_types;
use App\Models\TotalLeaves;
use App\Models\CreateAdminLeaves;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class leaveController extends Controller
{
    public function totalLeaves()
    {
        $totalLeaves = TotalLeaves::all();
        return view('leave.index', compact('totalLeaves'));
    }


    public function addleaveform(Request $request, $id = '')
    {

        if ($id > 0) {
            $arr = TotalLeaves::where(['id' => $id])->get();
            $result['id'] = $arr[0]->id;
            $result['Name'] = $arr[0]->Name;
            $result['Count'] = $arr[0]->Count;
            $result['Status'] = $arr[0]->Status;
        } else {
            $result['id'] = 0;
            $result['Name'] = '';
            $result['Count'] = '';
            $result['Status'] = '';
        }
        return view('leave.addleave', $result);
    }

    public function addleavedata(Request $req)
    {
        $req->validate([
            'Name' => [
                'required',
                'unique:total_leaves,Name,' . $req->post('id'),
            ],
        ]);
        if ($req->post('id') > 0) {
            $model = TotalLeaves::find($req->post('id'));
            $msg = 'Leave updated successfully';
        } else {
            $model = new TotalLeaves;
            $msg = 'Leave added successfully';
        }
        $model->Name = $req->post('Name');
        $model->Count = $req->post('count');
        $model->Status = $req->post('status');
        $model->save();
        return redirect()->route('totalleaves')->with('success', $msg);
    }

    public function delete($id)
    {
        $model = TotalLeaves::findOrFail($id);
        $model->delete();
        return redirect()->route('totalleaves')->with('success', 'Leave deleted successfully');
    }
    public function ApplyLeave()
    {
        $employee = Employee::whereNotIn('emp_status', ['5', '6'])
            ->get();

        $leaves = TotalLeaves::all();
        return view('leave.userleaveform', compact('employee', 'leaves'));
    }

    public function storeapplyleave(Request $request)
    {
        $startDate = Carbon::parse($request->startdate);
        $endDate = Carbon::parse($request->enddate);
        $daysBetween = $startDate->diffInDays($endDate) + 1;
        $leaveType = $request->leavetype;
        $totalAllowedLeaves = TotalLeaves::where('id', $leaveType)->first();
        if (!$totalAllowedLeaves) {
            return back()->with('error', 'Invalid leave type specified.');
        }
        $allowedLeaveDays = $totalAllowedLeaves->Count;

        // Calculate the current year
        $currentYear = Carbon::now()->year;
        // Fetch existing leaves for the employee within the current year
        $existingLeaves = CreateAdminLeaves::where('employee_id', $request->employee)
            ->whereYear('start_date', $currentYear)
            ->whereYear('end_date', $currentYear)
            ->where('status', 'Approved')
            ->get();

        // Initialize the total days taken for the specific leave type
        $totalDaysTakenForType = 0;

        foreach ($existingLeaves as $leave) {
            if ($leave->leave_type == $leaveType) {
                $leaveStart = Carbon::parse($leave->start_date);
                $leaveEnd = Carbon::parse($leave->end_date);
                $totalDaysTakenForType += $leaveStart->diffInDays($leaveEnd) + 1;
            }
        }


        // print_r($totalDaysTakenForType + $daysBetween);
        // print_r($allowedLeaveDays);
        // die();
        $existingPendingLeaves = CreateAdminLeaves::where('employee_id', $request->employee)
            ->where('status', 'pending')
            ->exists();

        if (($totalDaysTakenForType + $daysBetween) > $allowedLeaveDays) {
            if ($existingPendingLeaves) {
                return back()->with('error', 'Your request is already pending');
            } else {

                // return redirect()->back()->with('exceeds_limit', 'This is an unpaid leave, and salary will be deducted. Do you want to proceed?');        
                return redirect()->back()->with([
                    'exceeds_limit' => 'Please note that this is an unpaid leave, and your salary will be adjusted accordingly. Do you wish to proceed?',
                    'leave_details' => $request->only('employee', 'leavetype', 'startdate', 'enddate', 'reason', 'details')
                ]);
            }
        } else {
            if ($existingPendingLeaves) {
                return back()->with('error', 'Your request is already pending');
            } else {


                $leaveRequest = new CreateAdminLeaves();
                $leaveRequest->employee_id = $request->employee;
                $leaveRequest->paid = 'YES';
                $leaveRequest->status = "Pending";
                $leaveRequest->leave_type = $leaveType;
                $leaveRequest->start_date = $request->startdate;
                $leaveRequest->end_date = $request->enddate;
                $leaveRequest->no_of_leaves = $daysBetween;
                $leaveRequest->reason = $request->reason;
                // $leave->created_at = now();
                $leaveRequest->save();

                return back()->with('success', 'Leave request submitted successfully');
            }
        }
    }
    public function ApplyUnpaidLeave(Request $request)
    {
        $existingPendingLeaves = CreateAdminLeaves::where('employee_id', $request->employee)
            ->where('status', 'pending')
            ->exists();

        $startDate = Carbon::parse($request->startdate);
        $endDate = Carbon::parse($request->enddate);
        $daysBetween = $startDate->diffInDays($endDate) + 1;
        $leaveRequest = new CreateAdminLeaves();
        $leaveRequest->employee_id = $request->employee;
        $leaveRequest->paid = 'No';
        $leaveRequest->status = "Pending";
        $leaveRequest->leave_type = $request->leavetype;
        $leaveRequest->no_of_leaves = $daysBetween;
        $leaveRequest->start_date = $request->startdate;
        $leaveRequest->end_date = $request->enddate;
        $leaveRequest->reason = $request->reason;
        $leaveRequest->save();

        return back()->with('success', 'Leave request submitted successfully');
    }
    public function denied(Request $request, $id)
    {
        $leaveRequest = CreateAdminLeaves::findOrFail($id);
        $leaveRequest->status = "Declined";
        $leaveRequest->save();
        return back()->with('danger', 'Leave request declined');
    }

    public function approve(Request $request, $id)
    {
        $leaveRequest = CreateAdminLeaves::findOrFail($id);
        $leaveRequest->status = "Approved";
        $leaveRequest->save();
        return back()->with('success', 'Leave request approved');
    }
    public function ApproveLeaves(Request $request)
    {
        // Join employees table for filtering and ordering
        $query = CreateAdminLeaves::with(['TotalLeaves', 'Employee'])
            ->join('employees', 'create_admin_leaves.employee_id', '=', 'employees.id')
            ->select('create_admin_leaves.*')
            ->whereIn('status', ['Pending', 'Approved', 'Declined']);

        // 🔎 Employee Name Filter
        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('employees.firstname', 'like', $request->name . '%')
                    ->orWhere('employees.lastname', 'like', $request->name . '%');
            });
        }

        // 🔎 Leave Type Filter
        if ($request->filled('type')) {
            $query->whereHas('TotalLeaves', function ($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->type . '%');
            });
        }

        // 🔎 Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔎 Applied Date Filter
        if ($request->filled('applied_on')) {
            $query->whereDate('created_at', $request->applied_on);
        }

        // 📌 Ordering: employees.firstname → employees.lastname → status priority → created_at
        $leavereqest = $query->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderByRaw("
            CASE 
                WHEN status='Pending' THEN 0
                WHEN status='Approved' THEN 1
                ELSE 2
            END
        ")
            ->orderBy('created_at', 'desc')
            ->get();

        $types = TotalLeaves::all();

        return view('leave.approveleaves', compact('leavereqest', 'types'));
    }

    public function LeavesStatus(Request $request)
    {
        // Join employees table for filtering and ordering
        $query = CreateAdminLeaves::with(['TotalLeaves', 'Employee'])
            ->join('employees', 'create_admin_leaves.employee_id', '=', 'employees.id')
            ->select('create_admin_leaves.*');

        // 🔎 Employee Name Filter
        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('employees.firstname', 'like', $request->name . '%')
                    ->orWhere('employees.lastname', 'like', $request->name . '%');
            });
        }

        // 🔎 Leave Type Filter
        if ($request->filled('type')) {
            $query->whereHas('TotalLeaves', function ($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->type . '%');
            });
        }

        // 🔎 Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 🔎 Start Date Filter
        if ($request->filled('start')) {
            $query->whereDate('start_date', '>=', $request->start);
        }

        // 🔎 End Date Filter
        if ($request->filled('end')) {
            $query->whereDate('end_date', '<=', $request->end);
        }

        // 📌 Ordering: employees.firstname → employees.lastname → created_at
        $leavereqest = $query->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('leave.LeavesStatus', compact('leavereqest'));
    }

    public function approvereqest($id)
    {
        $totalLeaves = TotalLeaves::all();
        $leavereqest = CreateAdminLeaves::with('Employee')->findOrFail($id);
        $employee = $leavereqest->Employee;
        return view('leave.update', compact('leavereqest', 'totalLeaves', 'employee'));
    }

    public function updateRequest(Request $request, $id)
    {
        $request->validate([
            'leavetype' => 'required|exists:Total_Leaves,id',
            'status' => 'required|in:Pending,Approved,Declined',
            'startdate' => 'required|date',
            'enddate' => 'required|date|after_or_equal:startdate',
            'reason' => 'required|string|max:1000',
        ]);

        $leaveRequest = CreateAdminLeaves::findOrFail($id);

        $leaveRequest->leave_type = $request->leavetype;
        $leaveRequest->status = $request->status;
        $leaveRequest->start_date = $request->startdate;
        $leaveRequest->end_date = $request->enddate;

        $startDate = Carbon::parse($request->startdate);
        $endDate = Carbon::parse($request->enddate);
        $leaveRequest->no_of_leaves = $startDate->diffInDays($endDate) + 1;

        $leaveRequest->reason = $request->reason;
        $leaveRequest->save();

        return redirect()->route('ApproveLeaves')->with('success', 'Leave request updated successfully');
    }


    public function autodenied(Request $request, $id)
    {
        $leaveRequest = CreateAdminLeaves::findOrFail($id);
        $today = Carbon::today();
        $enddate = $leaveRequest->end_date;
        if ($enddate < $today) {
            $leaveRequest->status = "Declined";
            $leaveRequest->save();
        }
    }


    public function employeeleavehistroy(Request $request, $id)
    {
        $totalLeaves = TotalLeaves::all();
        $employeename = Employee::all();
        $leavereqest = CreateAdminLeaves::with(['TotalLeaves', 'Employee'])
            ->where('employee_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();
        return view('leave.employeeleavehistroy', compact('leavereqest'));
    }
}
