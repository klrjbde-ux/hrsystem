<?php

namespace App\Http\Controllers;

use App\Models\bonusDetuctionTypes;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\salary_count;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use App\Models\CreateAdminLeaves;
use App\Models\Deduction;
use App\Models\Salary_Deduction_Detail;
use App\Models\Salary_bonus_Detail;
use Illuminate\Support\Facades\Validator;

class SallaryController extends Controller
{
    public function salaryslip()
    {
        return view('salary.salaryslip');
    }

    public function salaryindex(Request $request)
    {
        $monthYearFormat = Carbon::now()->format('m/Y');

        $query = Salary_count::with('employee')
            ->join('employees', 'salary_counts.employee_id', '=', 'employees.id') // join to make filtering easier
            ->select('salary_counts.*')
            ->where('month', $monthYearFormat);

        // 🔎 Filter by employee name (starts with, case-insensitive)
        if ($request->filled('name')) {
            $name = strtolower($request->name);
            $query->where(function ($q) use ($name) {
                $q->whereRaw('LOWER(employees.firstname) LIKE ?', [$name . '%'])
                    ->orWhereRaw('LOWER(employees.lastname) LIKE ?', [$name . '%']);
            });
        }

        // 🔎 Filter by exact date
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // 🔎 Filter by salary range
        if ($request->filled('salary_range') && str_contains($request->salary_range, '-')) {
            [$min, $max] = explode('-', $request->salary_range);
            $min = (int) trim($min);
            $max = (int) trim($max);
            if ($min <= $max) {
                $query->whereBetween('gross_salary', [$min, $max]);
            }
        }

        // 📌 Ordering by employee name for readability
        $salary = $query->orderBy('employees.firstname')
            ->orderBy('employees.lastname')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('salary.index', compact('salary'));
    }
    public function Store(Request $request)
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthYearFormat = Carbon::now()->format('m/Y');

        $employees = Employee::all();

        foreach ($employees as $employee) {
            // Check if the salary record already exists for this employee and month
            $existingRecord = Salary_count::where('employee_id', $employee->id)
                ->where('month', $monthYearFormat)
                ->first();

            // If a record already exists, skip processing for this employee
            if ($existingRecord) {
                continue; // Skip to the next employee
            }

            $leaves = CreateAdminLeaves::where('employee_id', $employee->id)
                ->where('status', 'Approved')
                ->where('paid', 'No')
                ->get();

            $totalLeaves = 0;
            foreach ($leaves as $leave) {
                $totalLeaves += $leave->no_of_leaves;
            }

            $unpaidLeaves = 0;
            if ($totalLeaves > 24) {
                $unpaidLeaves = $totalLeaves - 24;
            }
            $perdaysalary = $employee->gross_salary / 30;

            $deductable = $perdaysalary * $unpaidLeaves;

            $payableSalary = $employee->gross_salary - $deductable;

            $salaryData = [
                'employee_id' => $employee->id,
                'month' => $monthYearFormat,
                'gross_salary' => $employee->gross_salary,
                'total_leaves' => $totalLeaves,
                'payable_salary'  => $payableSalary,


            ];


            $salaryRecord = new Salary_count($salaryData);
            $salaryRecord->save();
        }
        return redirect()->route('home')->with('success', 'Processed salaries successfully.');
    }
    public function addOrDetction($id)
    {
        $types = bonusDetuctionTypes::all();
        $salary = Salary_count::with('employee')->get();

        return view('salary.bonusDetuction', compact('salary', 'types'));
    }
    public function bonus($id = null)
    {
        $salary = 0;
        if (isset($id)) {
            $salary = Salary_count::with('employee')->find($id);
            $salaryBonus = Salary_bonus_Detail::where('salary_id', $id)->get();

            // Step to gather deduction types from the collection
            $bonus_types_ids = $salaryBonus->pluck('bonus_type')->toArray(); // Get an array of deduction types
            $bonus_types = bonusDetuctionTypes::whereIn('id', $bonus_types_ids)->get(); // Fetch corresponding deductions
        }

        $types = bonusDetuctionTypes::all();


        return view('salary.bonus', compact('salary', 'types', 'salaryBonus', 'bonus_types'));
    }

    public function deduction($id = null)
    {
        $salary = null; // Initialize as null
        $salaryDeductions = collect(); // Initialize as an empty collection
        $deduction_types = collect(); // Initialize as an empty collection

        if ($id) {
            $salary = Salary_count::with('employee')->find($id); // Fetch the salary instance
            $salaryDeductions = Salary_Deduction_Detail::where('salary_id', $id)->get();

            // Step to gather deduction types from the collection
            $deduction_types_ids = $salaryDeductions->pluck('deduction_type')->toArray(); // Get an array of deduction types
            $deduction_types = Deduction::whereIn('id', $deduction_types_ids)->get(); // Fetch corresponding deductions
        }
        $types = DB::table('deduction_type')->get(); //Fetch deduction types      
        return view('salary.deduction', compact('salary', 'types', 'salaryDeductions', 'deduction_types')); // Pass the salary and types to the view
    }
    public function update(Request $request, $id)
    {

        $messages = [
            'bonus.required' => 'The amount field is required',
            'bonus.max' => 'The amount is incorrect',
            'reason.required' => 'The reason field is required',
            'type.required' => 'The type field is required',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'bonus' => 'required|numeric|min:0|max:100000000', // Adjust rules as necessary
            'reason' => 'required|string|max:191', // Assuming reason is a string
            'type' => 'required', // Assuming type is a string

        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $monthYearFormat = Carbon::now()->format('m/Y');
        $salary = Salary_count::find($id);
        $Salary_bonus_Detail = new  Salary_bonus_Detail();
        // Inside your method
        $existingBonus = Salary_bonus_Detail::where('salary_id', $id)
            ->where('bonus_type', $request->type)
            ->where('month', $monthYearFormat)
            ->first();

        if ($existingBonus) {
            return redirect()->route('salary.index')->with('danger', 'This Bonus has already been recorded');
        }
        $Salary_bonus_Detail = new  Salary_bonus_Detail();

        // Fetch existing deductions for the salary ID
        $existingBonus = Salary_bonus_Detail::where('salary_id', $id)->get();
        $totalamount = $existingBonus->sum('bonus_amount');
        // Add the new deduction to the total amount
        $totalamount += $request->bonus;


        $salary = Salary_count::find($id);
        $oldsalary = $salary->payable_salary;
        $bonus = $request->bonus;
        $newsalary = $oldsalary + $bonus;
        $salary->payable_salary = $newsalary;
        $message = 'Bonus added';

        $salary->bonus = $totalamount;
        // $salary->deduction_reason = $request->reason;
        // $salary->deduction_type = $request->type;
        $Salary_bonus_Detail->salary_id = $id;
        $Salary_bonus_Detail->bonus_type = $request->type;
        $Salary_bonus_Detail->bonus_amount = $request->bonus;
        $Salary_bonus_Detail->bonus_reason = $request->reason;
        $Salary_bonus_Detail->month = $monthYearFormat;

        $Salary_bonus_Detail->save();
        $salary->save();
        return redirect()->route('salary.index')->with('success', $message . ' successfully');
    }

    public function deductionupdate(Request $request, $id)
    {
        $messages = [
            'deduct.required' => 'The amount field is required',
            'deduct.max' => 'The amount is incorrect',
            'reason.required' => 'The reason field is required',
            'type.required' => 'The type field is required',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'deduct' => 'required|numeric|min:0|max:100000000', // Adjust rules as necessary
            'reason' => 'required|string|max:191', // Assuming reason is a string
            'type' => 'required', // Assuming type is a string

        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $monthYearFormat = Carbon::now()->format('m/Y');
        $salary = Salary_count::find($id);
        $Salary_Deduction_Detail = new  Salary_Deduction_Detail();
        // Inside your method
        $existingDeduction = Salary_Deduction_Detail::where('salary_id', $id)
            ->where('deduction_type', $request->type)
            ->where('month', $monthYearFormat)
            ->first();

        if ($existingDeduction) {
            return redirect()->route('salary.index')->with('danger', 'This deduction has already been recorded');
        }

        //for calculate total amount
        // Fetch existing deductions for the salary ID
        $existingDeductions = Salary_Deduction_Detail::where('salary_id', $id)->get();
        $totalamount = $existingDeductions->sum('deduction_amount');
        // Add the new deduction to the total amount
        $totalamount += $request->deduct;
        $oldsalary = $salary->payable_salary;
        $deduct = $request->deduct;
        $newsalary = $oldsalary - $deduct;
        $salary->payable_salary = $newsalary;
        $message = 'Amount deducted';
        $salary->deduction = $totalamount;
        // $salary->deduction_reason = $request->reason;
        // $salary->deduction_type = $request->type;
        $Salary_Deduction_Detail->salary_id = $id;
        $Salary_Deduction_Detail->deduction_type = $request->type;
        $Salary_Deduction_Detail->deduction_amount = $request->deduct;
        $Salary_Deduction_Detail->deduction_reason = $request->reason;
        $Salary_Deduction_Detail->month = $monthYearFormat;


        $Salary_Deduction_Detail->save();
        $salary->save();
        return redirect()->route('salary.index')->with('success', $message . ' successfully');
    }
    public function slip()
    {
        return view('salary.slip');
    }

    public function deductiondelete($id)
    {
        // Fetch the salary deduction record or fail if it doesn't exist
        $salaryDeduction = Salary_Deduction_Detail::findOrFail($id);

        // Get the deduction amount and salary ID from the deduction record
        $deduction_amount = $salaryDeduction->deduction_amount;
        $salary_id = $salaryDeduction->salary_id;

        // Fetch existing deductions for the salary ID
        $existingDeductions = Salary_Deduction_Detail::where('salary_id', $salary_id)->get();

        // Calculate the total deductions amount
        $totalamount = $existingDeductions->sum('deduction_amount');

        // Deduct the amount of the record being deleted from the total
        $totalamount -= $deduction_amount; // Subtract the amount being deleted

        // Update the salary record
        $salary = Salary_count::findOrFail($salary_id);
        $salary->deduction = $totalamount; // Update the deduction amount


        //payable salary deduction
        $oldsalary = $salary->payable_salary;
        $newsalary = $oldsalary + $deduction_amount;
        $salary->payable_salary = $newsalary;


        $salary->save(); // Save the changes to the salary record

        // Delete the salary deduction record
        $salaryDeduction->delete();

        return redirect()->route('salary.index')->with('success', 'Deduction deleted successfully');
    }

    public function bonusdelete($id)
    {
        // Fetch the salary deduction record or fail if it doesn't exist
        $salaryBonus = Salary_bonus_Detail::findOrFail($id);

        // Get the deduction amount and salary ID from the deduction record
        $bonus_amount = $salaryBonus->bonus_amount;
        $salary_id = $salaryBonus->salary_id;

        // Fetch existing deductions for the salary ID
        $existingBonus = Salary_bonus_Detail::where('salary_id', $salary_id)->get();

        // Calculate the total deductions amount
        $totalamount = $existingBonus->sum('bonus_amount');

        // Deduct the amount of the record being deleted from the total
        $totalamount -= $bonus_amount; // Subtract the amount being deleted

        // Update the salary record
        $salary = Salary_count::findOrFail($salary_id);
        $salary->bonus = $totalamount; // Update the deduction amount

        //payable salary deduction
        $oldsalary = $salary->payable_salary;
        $newsalary = $oldsalary - $bonus_amount;
        $salary->payable_salary = $newsalary;

        $salary->save(); // Save the changes to the salary record

        // Delete the salary deduction record
        $salaryBonus->delete();

        return redirect()->route('salary.index')->with('success', 'Bonus deleted successfully');
    }
}
