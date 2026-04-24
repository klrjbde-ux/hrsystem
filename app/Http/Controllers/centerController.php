<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\EmpType;
use App\Models\EmpStatus;
use App\Models\Designation;
use App\Models\Department;
use App\Models\employeeContactRelation;

use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Mail\AccountCreated;
use FontLib\Table\Type\name;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
class centerController extends Controller
{
    public function gotoemploye($email){
       
        $employee = Employee::with(['empStatus', 'empType', 'Designation', 'Department', 'employeeContactRelation'])->findOrFail($email);
        return view('employe.empDetail', compact('employee'));
    }
}
