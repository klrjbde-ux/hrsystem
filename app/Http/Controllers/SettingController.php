<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Designation;

class SettingController extends Controller
{
    public function designationlist()
    {
        $designations = Designation::all();
        return view('Setting.designations', compact('designations'));
    }

    public function designationdestroy(Designation $designation)
    {
        $designation->delete();
        return redirect()->route('designationlist')->with('success', 'Designation deleted successfully');
    }

    public function form()
    {
        return view('Setting.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'designation_name' => 'required'
        ]);

        $designation = new Designation();
        $designation->designation_name = $request->designation_name;
        $designation->save();

        return redirect()->route('designationlist')->with('success', 'Designation added successfully');
    }
}
