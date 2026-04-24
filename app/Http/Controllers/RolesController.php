<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\MetaTrait;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
class RolesController extends Controller
{
    use MetaTrait;

    public function Show(){

        $this->meta['title'] = 'Create Role';

        return view('Roles.create', $this->metaResponse())->with('all_controllers', $this->routesList());
    }
    
   public function Store(Request $request)
{
    $role = Role::create([
        'name' => $request->input('name'), 
        'guard_name' => 'web', 
    ]);
        if ($request->permissions) {
            foreach ($request->permissions as $value) {
                $data = [
                    'guard_name' => 'web',
                    'name'       => $value,
                ];

                $permission = Permission::where($data)->first();
                if (! isset($permission->id)) {
                    $permission = Permission::create($data);
                }
                $role->givePermissionTo($permission);
            }
        }
    return redirect()->route('create.Roles')->with('success', 'Role created successfully!');
}
public function routesList()
{
    $all_controllers = [];
    $routeList = \Route::getRoutes();

    foreach ($routeList as $route) {
        $action = $route->getAction();
        if (isset($action['controller']) && Str::contains($action['controller'], 'Controller@') == true) {
            $row = explode('@', $action['controller']);
            
            $index = str_replace("App\Http\Controllers\\", '', $row[0]);
            $index = str_replace('Auth\\', '', $index);
            if (
                $index == 'LoginController' ||
                $index == 'RegisterController' ||
                $index == 'ForgotPasswordController' ||
                $index == 'ForgotPasswordController' ||
                $index == 'ConfirmPasswordController' ||
                $index == 'Laravel\Sanctum\Http\Controllers\CsrfCookieController' ||
                $index == 'centerController' ||
                $index == 'HomeController' ||
                $index == 'ResetPasswordController'
            ) {
                continue;
            }
            
            $index = explode('Controller', $index);
            
            $all_controllers[$index[0]][] = $row[1];

        }
    }
    return $all_controllers;
}
}
