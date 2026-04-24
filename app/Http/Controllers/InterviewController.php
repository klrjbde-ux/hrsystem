<?php

namespace App\Http\Controllers;

use App\Models\Interview;
use Illuminate\Http\Request;

class InterviewController extends Controller
{
    /**
     * Display list of candidates
     */
    public function index(Request $request)
    {
        $query = Interview::query();

        if ($request->filled('candidate')) {
            $query->where('name', 'like', '%' . $request->candidate . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('interview_date')) {
            $query->whereDate('interview_date', $request->interview_date);
        }

        $interviews = $query->latest()->get();

        return view('interviews.index', compact('interviews'));
    }

    /**
     * Show add form
     */
    public function create()
    {
        return view('interviews.create');
    }

    /**
     * Store new candidate
     */
    public function store(Request $request)
    {
        $data = $request->all();

        // CV upload
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cv'), $filename);
            $data['cv'] = $filename;
        }

        Interview::create($data);

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Candidate added successfully');
    }

    /**
     * Show single record (optional)
     */
    public function show(Interview $interview)
    {
        return view('interviews.show', compact('interview'));
    }

    /**
     * Show edit form
     */
    public function edit(Interview $interview)
    {
        return view('interviews.edit', compact('interview'));
    }

    /**
     * Update candidate
     */
    public function update(Request $request, Interview $interview)
    {
        $data = $request->all();

        // CV upload
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/cv'), $filename);
            $data['cv'] = $filename;
        }

        $interview->update($data);

        return redirect()
            ->route('interviews.index')
            ->with('success', 'Candidate updated successfully');
    }

    /**
     * Delete candidate
     */
    public function destroy(Interview $interview)
    {
        $interview->delete();

        return back()->with('success', 'Candidate deleted successfully');
    }
}
