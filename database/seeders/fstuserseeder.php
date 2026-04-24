<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\User;

class fstuserseeder extends Seeder
{
    public function run()
    {
        // -------------------------
        // CREATE NORMAL USER
        // -------------------------
        $userId = DB::table('users')->insertGetId([
            'name' => 'First User',
            'email' => 'user@user.com',
            'emp_status' => '1',
            'password' => Hash::make('12345678'),
        ]);

        // -------------------------
        // CREATE ADMIN USER
        // -------------------------
        $adminId = DB::table('users')->insertGetId([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'emp_status' => '1',
            'password' => Hash::make('admin123'),
        ]);

        // -------------------------
        // CREATE HR USER
        // -------------------------
        $hrId = DB::table('users')->insertGetId([
            'name' => 'HR Manager',
            'email' => 'hr@hr.com',
            'emp_status' => '1',
            'password' => Hash::make('hr123'),
        ]);

        // -------------------------
        // ASSIGN ROLES (Spatie)
        // -------------------------
        $adminRole = Role::where('name', 'admin')->first();
        $hrRole = Role::where('name', 'hr_manager')->first();
        $employeeRole = Role::where('name', 'employee')->first();

        DB::table('model_has_roles')->insert([
            [
                'role_id' => $adminRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $adminId,
            ],
            [
                'role_id' => $hrRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $hrId,
            ],
            [
                'role_id' => $employeeRole->id,
                'model_type' => 'App\Models\User',
                'model_id' => $userId,
            ],
        ]);
    }
}