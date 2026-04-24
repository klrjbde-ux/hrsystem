<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class EmpStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status = [
            ['status' => 'Permanent'],
            ['status' => 'Contractual'],
            ['status' => 'Probation'],
            ['status' => 'Intern'],
            ['status' => 'Resign'],
            ['status' => 'Terminate'],
            ['status' => 'Onleave'],
        ];

        DB::table('emp_status')->insert($status);
        
    }
}
