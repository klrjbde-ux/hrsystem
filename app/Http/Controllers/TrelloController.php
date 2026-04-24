<?php

namespace App\Http\Controllers;
use App\Models\Board;
use App\Models\Employee;
use Illuminate\Http\Request;

class TrelloController extends Controller
{
    // Show form
    public function create()
    {
        $employees = Employee::all();
        return view('trello.create', compact('employees'));
    }

    // Store project
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'assigned_to' => 'required'
        ]);

        Board::create([
            'title' => $request->title,
            'assigned_to' => $request->assigned_to
        ]);

        return redirect()->back()->with('success', 'Project created successfully');
    }
}

