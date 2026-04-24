<?php

namespace App\Http\Controllers;

use App\Models\Policy;
use Illuminate\Http\Request;

class PolicyController extends Controller
{
    // List all policies
    public function index()
    {
        $policies = Policy::latest()->get(); // Get all policies, latest first
        return view('officepolicy.index', compact('policies')); // <-- fixed variable name
    }

    // Show create form
    public function create()
    {
        return view('officepolicy.create');
    }

    // Save new policy
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        Policy::create($validated);

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy created successfully');
    }

    // Show edit form
    public function edit(Policy $policy)
    {
        return view('officepolicy.edit', compact('policy'));
    }

    // Update policy
    public function update(Request $request, Policy $policy)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        $policy->update($validated);

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy updated successfully');
    }

    // Delete policy
    public function destroy(Policy $policy)
    {
        $policy->delete();

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy deleted successfully');
    }
}
