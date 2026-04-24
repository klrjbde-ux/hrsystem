<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Employee;
use App\Models\salary_count;
use App\Models\Signature;
use App\Models\Salary_bonus_Detail;
use App\Models\Salary_Deduction_Detail;
use App\Models\bonusDetuctionTypes;
use App\Models\Deduction;

class PdfController extends Controller
{
    public function generateSlipPDF($id, $employee_id)
    {
        try {

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

            $pdf = PDF::loadView('salary.slip', compact(
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


            return $pdf->download('salary_slip_' . $employee_id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
