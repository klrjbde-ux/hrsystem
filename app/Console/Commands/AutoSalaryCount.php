<?php

namespace App\Console\Commands;

use App\Models\BonusDetuctionTypes;
use App\Models\SalaryCount;
use App\Models\Employee;
use App\Models\CreateAdminLeaves;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AutoSalaryCount extends Command
{
    protected $signature = 'command:autosalarycount';
    protected $description = 'Calculate and store employee salaries based on leaves and gross salary';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Starting salary processing...');

        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $monthYearFormat = Carbon::now()->format('m/Y');

        Employee::chunk(100, function ($employees) use ($monthYearFormat) {
            foreach ($employees as $employee) {
                try {
                    $this->processEmployeeSalary($employee, $monthYearFormat);
                } catch (\Exception $e) {
                    Log::error("Failed to process salary for employee ID: {$employee->id}. Error: {$e->getMessage()}");
                    $this->error("Failed to process salary for employee ID: {$employee->id}");
                }
            }
        });

        $this->info('Salaries processed successfully.');
        return 0;
    }

    private function processEmployeeSalary(Employee $employee, $monthYearFormat)
    {
        $leaves = CreateAdminLeaves::where('employee_id', $employee->id)
                    ->get(); 

        $totalLeaves = $leaves->sum('no_of_leaves');
        $unpaidLeaves = max(0, $totalLeaves - 24);

        $perDaySalary = $employee->gross_salary / 30;
        $deductable = $perDaySalary * $unpaidLeaves;
        $payableSalary = $employee->gross_salary - $deductable;

        $salaryData = [
            'employee_id' => $employee->id,
            'month' => $monthYearFormat,
            'gross_salary' => $employee->gross_salary,
            'total_leaves' => $totalLeaves,
            'payable_salary' => $payableSalary,
        ];
        SalaryCount::create($salaryData);
    }
}
