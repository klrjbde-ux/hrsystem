<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $statuses = [
            ['status' => 'present(on time)'],
            ['status' => 'present(late)'],
            ['status' => 'present(short leave)'],
            ['status' => 'absent'],
            ['status' => 'full leave'],

        ];
        DB::table('attendance_status')->insert($statuses);
    }
}