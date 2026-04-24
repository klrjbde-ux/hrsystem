<?php

namespace Database\Seeders;
use Illuminate\Support\Facades\DB;

use Illuminate\Database\Seeder;

class bonus_type extends Seeder
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
                'status' => 'Bonus', 
                'function' => 0, 
            ],
            [
                'status' => 'Dearness Allowance', 
                'function' => 0, 
            ],
            [
                'status' => 'House Rent Allowance', 
                'function' => 0, 
            ],
            [
                'status' => 'Conveyance', 
                'function' => 0, 
            ],
         
            [
                'status' => 'Medical Expenses', 
                'function' => 0, 
            ],
            [
                'status' => 'Special', 
                'function' => 0, 
            ],
            [
                'status' => 'Other', 
                'function' => 0,
            ]
        ];

        DB::table('bonus_types')->insert($types); 
    }
}
