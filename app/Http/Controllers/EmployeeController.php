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
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Mail\AccountCreated;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class EmployeeController extends Controller
{
  public function store(Request $request)
{
    // Validate request
    $validated = $request->validate([
        'personal_email' => 'required|email|unique:employees,personal_email',
        'contact_no' => 'required|regex:/^03[0-9]{9}$/',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        'relation_id' => 'nullable|exists:employee_contact_relations,id',
    ]);

    // Create new employee
    $employee = new Employee();
    $employee->firstname = $request->firstname;
    $employee->lastname = $request->lastname;
    $employee->personal_email = $request->personal_email;
    $employee->gender = $request->gender;
    $employee->dob = $request->dob;
    $employee->emp_type = $request->emp_type;
    $employee->emp_status = $request->emp_status;
    $employee->designation = $request->designation;
    $employee->department = $request->department;
    $employee->joining_date = $request->joining_date;
    $employee->contact_no = $validated['contact_no'];
    $employee->identity_no = $request->identity_no;
    $employee->permanent_address = $request->permanent_address;
    $employee->current_address = $request->current_address;
    $employee->emergency_contact = $request->emergency_contact;

    // Assign relation_id instead of relation
    $employee->relation_id = $request->relation_id ?? null;

    $employee->emergency_contact_address = $request->emergency_contact_address;
    $employee->gross_salary = $request->gross_salary;

    // Handle image upload
    if ($request->hasFile('image')) {
        $image = $request->file('image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/assets/profile_images', $imageName);
        $employee->image = $imageName;
    }

    $employee->save();

    // Create corresponding user account
    $name = $request->firstname . ' ' . $request->lastname;
    $email = $request->personal_email;
    $password = '12345678';
    $role = $request->role;

    $user = User::create([
        'name' => $name,
        'email' => $email,
        'password' => Hash::make($password),
    ]);

    $user->assignRole($role);

    try {
        Mail::to($email)->send(new AccountCreated($email, $name, $password));
    } catch (\Throwable $th) {
        // Optional: log the exception
    }

    return redirect()->route('employees.index')->with('success', 'Employee added successfully');
}




    public function EmployeDetails()
    {
        $empTypes = EmpType::all();
        $EmpStatus = EmpStatus::whereNotIn('id', [5, 6, 7])->get();
        $designation = Designation::all();
        $departments = Department::all();
        $relations = employeeContactRelation::all();
        $user = User::all();
        $role = Role::all();
        return view('Employe.create', compact('empTypes', 'EmpStatus', 'designation', 'departments', 'relations', 'role'));
    }
    public function index(Request $request)
    {
        $role = Auth::user()->roles->pluck('name')->first();
        $query = Employee::query();

        // Non-admin sees only their own record
        if ($role !== 'admin') {
            $query->where('personal_email', Auth::user()->email);
        }

      if ($request->filled('search')) {

        $search = trim($request->search);
        $query->whereRaw("CONCAT(firstname,' ',lastname) LIKE ?", ["%{$search}%"]);
}

// if ($request->filled('search'))
//  {
//     $query->where('firstname', 'like', $request->search . '%');
//   }

        // Filter by emp_type
        if ($request->filled('emp_type')) {
            $query->where('emp_type', $request->emp_type);
        }

        // Filter by emp_status
        if ($request->filled('emp_status')) {
            $query->where('emp_status', $request->emp_status);
        }

        // Filter by designation
        if ($request->filled('designation')) {
            $query->where('designation', $request->designation);
        }

        // Filter by department
        if ($request->filled('department')) {
            $query->where('department', $request->department);
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Filter by joining date
        if ($request->filled('joining_date_from')) {
            $query->where('joining_date', '>=', $request->joining_date_from);
        }
        if ($request->filled('joining_date_to')) {
            $query->where('joining_date', '<=', $request->joining_date_to);
        }

        // Fetch filtered employees with pagination
        $employees = $query->orderBy('firstname')->orderBy('lastname')->paginate(10);

        // For dropdown filters
        $types = EmpType::all();
        $statuses = EmpStatus::all();
        $designations = Designation::all();
        $departments = Department::all();

        return view('Employe.index', compact('employees', 'types', 'statuses', 'designations', 'departments'));
    }


    public function show($id)
    {
        $employee = Employee::with(['empStatus', 'empType', 'Designation', 'Department', 'employeeContactRelation'])
            ->findOrFail($id);
        return view('employe.detail', compact('employee'))
            ->with('section', 'overview');
    }

public function update(Request $request, $id)
{
   
    $employee = Employee::findOrFail($id);
    $emp_email = $employee->personal_email;
    $user = User::where('email', $emp_email)->first();
    // Check if the employee status is being updated to "terminated"
    if ($request->emp_status === '6') {
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee terminated successfully!');
    }

    // Check if the employee status is being updated to "resigned"
    if ($request->emp_status === '5') {
        // Perform soft delete
        $employee->delete();
        return redirect()->route('employees.index')->with('success', 'Employee resigned successfully!');
    }
    // Handle image upload
    if ($request->hasFile('image')) {
        try {
            // Validate the image
            $validator = Validator::make($request->all(), [
                'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            ]);

            if ($validator->fails()) {
            } else {
                // Delete old image if exists
                if ($employee->image) {
                    $oldImagePath = 'public/assets/profile_images/' . $employee->image;
                    if (Storage::exists($oldImagePath)) {
                        Storage::delete($oldImagePath);
                    }
                }

                // Upload new image
                $image = $request->file('image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $stored = $image->storeAs('public/assets/profile_images', $imageName);

                if ($stored) {
                    $employee->image = $imageName;
                }
            }
        } catch (\Exception $e) {
    dd($e->getMessage());
}
    }
    $employee->firstname = $request->firstname;
    $employee->lastname = $request->lastname;
    $employee->personal_email = $request->personal_email;
    $employee->gender = $request->gender;
    $employee->dob = $request->dob;
    $employee->emp_type = $request->emp_type;
    $employee->emp_status = $request->emp_status;
    $employee->designation = $request->designation;
    $employee->department = $request->department;
    $employee->branch = $request->branch;
    $employee->joining_date = $request->joining_date;
    $employee->contact_no = $request->contact_no;
    $employee->identity_no = $request->identity_no;
    $employee->permanent_address = $request->permanent_address;
    $employee->current_address = $request->current_address;
    $employee->emergency_contact = $request->emergency_contact;
    $employee->relation_id = $request->relation;
    $employee->emergency_contact_address = $request->emergency_contact_address;
    $employee->gross_salary = $request->gross_salary;

  try {
    $employee->save();
} catch (\Exception $e) {
    dd($e->getMessage()); // 🔥 show real error
}

    if ($user) {
        $user_id = $user->id;

        try {
            // Update the user table
            DB::table('users')
                ->where('id', $user_id)
                ->update(['email' => $request->personal_email]);

            $roleAssignment = DB::table('model_has_roles')->where('model_id', $user_id)->first();
            if ($roleAssignment) {
                // Update the role assignment
                DB::table('model_has_roles')
                    ->where('model_id', $user_id)
                    ->update(['role_id' => $request->role]);

            }
        } catch (\Exception $e) {

        }
    }
    return redirect()->route('employees.index')->with('success', 'Employee updated successfully!');
}

    public function edit($id)
    {
        $empTypes = EmpType::all();
        $EmpStatus = EmpStatus::all();
        $designations = Designation::all();
        $departments = Department::all();
        $relations = employeeContactRelation::all();
        $employee = Employee::with('designation')->findOrFail($id);
        $emp_email = $employee->personal_email;
        $user = User::where('email', $emp_email)->first();
        if ($user) {
            $user_id = $user->id;
            $role = DB::table('model_has_roles')->where('model_id', $user_id)->first();
            if ($role) {
                $user_role = Role::where('id', $role->role_id)->first();
            } else {
                $user_role = null;
            }
        } else {
            $user_role = null;
        }

        $roles = Role::all();
        return view('Employe.edit', compact('employee', 'empTypes', 'EmpStatus', 'designations', 'departments', 'relations', 'roles', 'user_role'));
    }





    // *
    // *
    // DELETE EMPLOYEE
    // *
    // *

    public function delete($id)
    {
        $employee = Employee::findOrFail($id);
        $emp_email = $employee->personal_email;
        $user = User::where('email', $emp_email)->first();
        $employee->forcedelete();
        $user->forcedelete();
        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
    public function updateProfile(Request $request)
{
    $validator = Validator::make($request->all(), [
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $user = Auth::user();

    // Find employee (may or may not exist)
    $employee = Employee::where('personal_email', $user->email)->first();

    if ($request->file('image')) {

        // Delete old user image
        if ($user->image) {
            $oldPath = 'public/assets/profile_images/' . $user->image;
            if (Storage::exists($oldPath)) {
                Storage::delete($oldPath);
            }
        }

        // Upload new image
        $image = $request->file('image');
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/assets/profile_images', $imageName);

        // ✅ ALWAYS update user
        $user->image = $imageName;
        $user->save();

        // ✅ Update employee IF exists
        if ($employee) {
            $employee->image = $imageName;
            $employee->save();
        }
    }

    return redirect()->back()->with('profile_updated', 'Profile updated successfully');
}

    public function changePassword(Request $request)
    {
        $messages = [
            'password.required' => 'The current password field is required',
            'newpassword.required' => 'The new password field is required',
            'newpassword.min' => 'The new password must be at least 8 characters long',
            'renewpassword.required' => 'The confirmation password field is required',
            'renewpassword.same' => 'The confirmation password does not match',
        ];
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'password' => 'required',
            'newpassword' => 'required|min:8',
            'renewpassword' => 'required|same:newpassword',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput()->with('section', 'profile-change-password');
        }

        $user = Auth::user();

        if (!Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->withErrors(['password' => 'The current password is incorrect.'])
                ->with('section', 'profile-change-password');
        }

        $user->password = Hash::make($request->input('newpassword'));

        $user->save();
        session()->flash('password_updated', 'Password Updated Sucessfully');
        return redirect()->back()
            ->with('section', 'profile-change-password');
    }


    public function account_setting($id)
    {
        $empTypes = EmpType::all();
        $EmpStatus = EmpStatus::all();
        $designations = Designation::all();

        $departments = Department::all();
        $relations = employeeContactRelation::all();
        $employee = Employee::with('designation')->findOrFail($id);
        $emp_email = $employee->personal_email;
        $user = User::where('email', $emp_email)->first();
        if ($user) {
            $user_id = $user->id;
            $role = DB::table('model_has_roles')->where('model_id', $user_id)->first();

            if ($role) {
                $user_role = Role::where('id', $role->role_id)->first();
            } else {
                $user_role = null;
            }
        } else {
            $user_role = null;
        }

        $roles = Role::all();
        return view('Employe.accountsetting', compact('employee', 'empTypes', 'EmpStatus', 'designations', 'departments', 'relations', 'roles', 'user_role'));
    }
    public function myProfile()
{
    $user = Auth::user();

    // Try to find employee via email
    $employee = Employee::where('personal_email', $user->email)->first();

    return view('Employe.myprofile_admin', compact('user', 'employee'));
}
}
