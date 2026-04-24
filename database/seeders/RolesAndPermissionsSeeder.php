<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Permission::create(['name' => 'CREATE']);
        Permission::create(['name' => 'EDIT']);
        Permission::create(['name' => 'DELETE']);
        Permission::create(['name' => 'VIEW']);

        // Leave Management permissions (for sidebar & access)
        Permission::firstOrCreate(['name' => 'web:Leave:leavereqestindex', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'web:Leave:leavereqest', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'web:Leave:leaveform', 'guard_name' => 'web']);

        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        $role = Role::create(['name' => 'hr_manager']);
        $role->givePermissionTo([
            'CREATE', 'EDIT', 'VIEW',
            'web:Leave:leavereqestindex',  // Approve Leaves
            'web:Leave:leavereqest',       // Leaves Status
            'web:Leave:leaveform',         // Apply leave
        ]);

        $role = Role::create(['name' => 'employee']);
        $role->givePermissionTo([
            'VIEW',
            'web:Leave:leavereqest',   // Leaves Status
            'web:Leave:leaveform',     // Apply leave
        ]);
    }
}
