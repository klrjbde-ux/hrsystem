<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmpTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $types = [
            ['type' => 'Work from Office'],
            ['type' => 'Work Remotely'],
        ];

        DB::table('emp_types')->insert($types);
    }
}
