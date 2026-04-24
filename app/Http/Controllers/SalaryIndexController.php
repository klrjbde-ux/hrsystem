<?php

namespace App\Http\Controllers;
use App\Models\Department;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Signature;
use App\Models\salary_count;
use App\Models\Salary_bonus_Detail;
use App\Models\bonusDetuctionTypes;

use App\Models\Salary_Deduction_Detail;
use App\Models\Deduction;
use Illuminate\Support\Facades\Auth;

class SalaryIndexController extends Controller
{
   
    public function slip($id, $employee_id) {
        $employee = Employee::with(['empStatus', 'empType', 'designation', 'department'])
                     ->where('id', $employee_id)
                     ->first();
        $salary = Salary_count::where('id', $id)->first();
        $gross_salary = $salary->gross_salary;
    
        // Retrieve the deduction based on deduction_type
        $deduction = Deduction::where('id', $salary->deduction_type)->first();
    
        // Retrieve the bonus 
        $salary_bonus_detail = Salary_bonus_Detail::where('salary_id', $id)->get();
        $totalamount = $salary_bonus_detail->sum('bonus_amount') + $gross_salary;    
        $allbonustype = bonusDetuctionTypes::all();
        $alldeductiontype = Deduction::all();

        
      
                       

        // Retrieve the deduction 
        $salary_deduction_detail = Salary_Deduction_Detail::where('salary_id', $id)->get();
        $totalamountdeduction = $salary_deduction_detail->sum('deduction_amount');
        $totalamountdeductionssalary = $totalamountdeduction - $gross_salary;


        //signature
        $signature = Signature::all();
    
        return view('salary.salaryslip', compact(
            'employee', 
            'salary', 
            'deduction', 
            'salary_bonus_detail', 
            'totalamount', 
            'salary_deduction_detail',
            'totalamountdeduction', 
            'totalamountdeductionssalary', 
            'allbonustype',
            'alldeductiontype',
            'signature',
            
            
        ));
    }
    
}
