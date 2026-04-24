<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class emergenctContactRelationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emp_relation = [
            ['contact_name' => 'Father'],
            ['contact_name' => 'Mother'],
            ['contact_name' => 'Brother'],
        ];

        DB::table('employee_contact_relations')->insert($emp_relation);
    }
}
