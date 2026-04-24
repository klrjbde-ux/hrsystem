<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LeaveTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $leaves = [
            [
                'name' => 'Sick Leave', 
                'count' => '7', 
                'status' => 'active',
            ],
            [
                'name' => 'Casual Leave',
                'count' => '7',
                'status' => 'inactive',
            ],
        ];

        DB::table('total_leaves')->insert($leaves); 
    }
}
