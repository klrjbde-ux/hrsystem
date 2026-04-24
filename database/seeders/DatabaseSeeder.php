<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    // public function run()
    // {
    //     $this->call([
    //         EmpStatusTableSeeder::class,
    //         // any other seeders you have
    //     ]);
    //     $this->call(emergenctContactRelationSeeder::class);

    // }


    public function run()
    {
        $this->call([
            
            EmpStatusTableSeeder::class,
            EmpTypesTableSeeder::class,
            emergenctContactRelationSeeder::class,
            leaveTypesSeeder::class,
            department::class,
            DesignationTableSeeder::class,
            RolesAndPermissionsSeeder::class,
            fstuserseeder::class,
            bonus_type::class,
            deduction_type::class,
            AttendanceStatusSeeder::class,

        ]);
        $this->call([
        ]);
    }
}