<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\TotalLeaves;
use App\Models\Attendance;
use App\Models\CreateAdminLeaves;
use App\Models\Appraisal;
use App\Models\PerformanceReview;
use App\Models\User;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   $develper = Employee::where('Department', '2')->count();
        $hr = Employee::where('Department', '1')->count();
        $sqa = Employee::where('Department', '3')->count();
        $today = Carbon::today()->toDateString(); 
        $presentEmp = Attendance::where('date', $today)->count();
        $todayAttendance = Attendance::with('employee')
            ->whereDate('date', $today)
            ->latest('id')
            ->get();
        $totalLeaves = TotalLeaves::all();
        $employeename = Employee::all();
        $leavereqest = CreateAdminLeaves::with(['TotalLeaves', 'Employee'])
        ->orderBy('created_at', 'desc')
        ->get();
        $employees = Employee::count();
        $absentemp =$employees - $presentEmp;
        $user = User::where('id', '5')->with('roles')->first();
        //
        $terminated_employee = Employee::where('emp_status', '6')->count();
        $resigned_employee = Employee::where('emp_status', '5')->count();
        $all_employees = Employee::count(); // Total employees
        // Calculate working employees
         $working_employees = $all_employees - ($terminated_employee + $resigned_employee);

        $todayAppraisals = Appraisal::with(['employee', 'reviewer'])
            ->whereDate('review_date', $today)
            ->latest('id')
            ->take(8)
            ->get();

        $reviewsByStatusToday = PerformanceReview::whereDate('created_at', $today)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $totalReportsToday = PerformanceReview::whereDate('created_at', $today)->count()
            + Appraisal::whereDate('review_date', $today)->count();

        return view('home', compact('employees', 'user','leavereqest', 'presentEmp', 'absentemp', 'hr', 'develper', 'sqa'
        , 'terminated_employee','resigned_employee','working_employees', 'todayAttendance', 'todayAppraisals', 'reviewsByStatusToday', 'totalReportsToday'));
    }
}
