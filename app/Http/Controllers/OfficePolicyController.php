<?php

namespace App\Http\Controllers;

use App\Models\OfficePolicy;
use Illuminate\Http\Request;

class OfficePolicyController extends Controller
{
    public function index(Request $request)
    {
        $query = OfficePolicy::query();

        if ($request->filled('title')) {
            $query->where('title', 'like', '%' . trim($request->title) . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('created_at')) {
            $query->whereDate('created_at', $request->created_at);
        }

        $policies = $query->latest()->get();

        return view('officepolicy.index', compact('policies'));
    }

    public function create()
    {
        return view('officepolicy.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        OfficePolicy::create($validated);

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy created successfully');
    }

    public function edit(OfficePolicy $officePolicy)
    {
        return view('officepolicy.edit', compact('officePolicy'));
    }

    public function update(Request $request, OfficePolicy $officePolicy)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'status' => 'required|in:Active,Inactive',
        ]);

        $officePolicy->update($request->all());

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy updated successfully');
    }

    public function destroy(OfficePolicy $officePolicy)
    {
        $officePolicy->delete();

        return redirect()->route('officepolicy.index')
            ->with('success', 'Policy deleted successfully');
    }
}
