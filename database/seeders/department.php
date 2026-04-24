<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class department extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      
      DB::table('department1')->insert([
            ['department_name' => 'HR', ],
            ['department_name' => 'SQA', ],
            ['department_name' => 'Developer', ],
           
        ]);
    }
}
