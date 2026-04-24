<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class deduction_type extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'status' => 'Contribution to Pdf', 
                'function' => 0, 
            ],
            [
                'status' => 'Salary Advance', 
                'function' => 1,
            ],
            [
                'status' => 'Professional Tax', 
                'function' => 1,
            ],
            [
                'status' => 'TDS', 
                'function' => 1,
            ],
            [
                'status' => 'Upaid Leaves', 
                'function' => 1,
            ]
        
        ];

        DB::table('deduction_type')->insert($types); 
    }
}
