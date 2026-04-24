<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AssignLeavePermissionsToHrSeeder extends Seeder
{
    /**
     * hr_manager: Approve Leaves + Leaves Status + Apply leave
     * employee: sirf Apply leave + Leaves Status
     * Run: php artisan db:seed --class=AssignLeavePermissionsToHrSeeder
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $allLeavePermissions = [
            'web:Leave:leavereqestindex',  // Approve Leaves
            'web:Leave:leavereqest',       // Leaves Status
            'web:Leave:leaveform',         // Apply leave
        ];

        foreach ($allLeavePermissions as $name) {
            Permission::firstOrCreate(
                ['name' => $name, 'guard_name' => 'web']
            );
        }

        // HR: all 3 options
        $hrRole = Role::where('name', 'hr_manager')->where('guard_name', 'web')->first();
        if ($hrRole) {
            $hrRole->givePermissionTo($allLeavePermissions);
            $this->command->info('Leave Management (Approve Leaves, Leaves Status, Apply leave) assigned to hr_manager.');
        } else {
            $this->command->warn('hr_manager role not found.');
        }

        // Employee: sirf Apply leave + Leaves Status
        $employeeLeavePermissions = [
            'web:Leave:leavereqest',   // Leaves Status
            'web:Leave:leaveform',     // Apply leave
        ];
        $employeeRole = Role::where('name', 'employee')->where('guard_name', 'web')->first();
        if ($employeeRole) {
            $employeeRole->givePermissionTo($employeeLeavePermissions);
            $this->command->info('Leave Management (Apply leave, Leaves Status) assigned to employee.');
        } else {
            $this->command->warn('employee role not found.');
        }
    }
}
