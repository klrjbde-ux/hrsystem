<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Appraisal;
use App\Models\Employee;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppraisalController extends Controller
{
    public function index()
    {
        $appraisals = Appraisal::with(['employee', 'performanceReview', 'reviewer'])
            ->orderBy('review_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('performance.appraisals.index', compact('appraisals'));
    }
    public function create()
    {
        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();
        $reviews = PerformanceReview::orderBy('period_end', 'desc')->get();
        $reviewers = Employee::orderBy('firstname')->orderBy('lastname')->get();
        return view('performance.appraisals.create', compact('employees', 'reviews', 'reviewers'));
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'performance_review_id' => 'required|exists:performance_reviews,id',
            'rating' => 'nullable|numeric|min:0|max:10',
            'comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'reviewer_id' => 'nullable|exists:employees,id',
            'review_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $pendingAppraisal = Appraisal::where('employee_id', $request->employee_id)
            ->where('status', 'pending')
            ->exists();
        if ($pendingAppraisal) {
            return redirect()->back()
                ->with('danger', 'This employee already has a pending appraisal. Please complete or approve it before adding a new one.')
                ->withInput();
        }
        try {
            $appraisal = Appraisal::create([
                'employee_id' => $request->employee_id,
                'performance_review_id' => $request->performance_review_id,
                'reviewer_id' => $request->reviewer_id,
                'rating' => $request->rating,
                'comments' => $request->comments,
                'recommendations' => $request->recommendations,
                'review_date' => $request->review_date ?? now(),
                'status' => $request->status ?? 'pending',
            ]);

        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Error creating appraisal.');
        }
        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal created successfully.');
    }
    public function edit($id)
    {
        $appraisal = Appraisal::findOrFail($id);
        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();
        $reviews = PerformanceReview::orderBy('period_end', 'desc')->get();
        $reviewers = Employee::orderBy('firstname')->orderBy('lastname')->get();

        return view('performance.appraisals.edit', compact('appraisal', 'employees', 'reviews', 'reviewers'));
    }
    public function update(Request $request, $id)
    {
        $appraisal = Appraisal::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'performance_review_id' => 'required|exists:performance_reviews,id',
            'rating' => 'nullable|numeric|min:0|max:10',
            'comments' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'reviewer_id' => 'nullable|exists:employees,id',
            'review_date' => 'nullable|date',
            'status' => 'required|in:pending,completed,acknowledged',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $appraisal->update($request->only([
                'employee_id',
                'performance_review_id',
                'reviewer_id',
                'rating',
                'comments',
                'recommendations',
                'review_date',
                'status',
            ]));
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Error updating appraisal.');
        }
        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal updated successfully.');
    }
    public function destroy($id)
    {
        $appraisal = Appraisal::findOrFail($id);
        $appraisal->delete();
        return redirect()->route('performance.appraisals.index')
            ->with('success', 'Appraisal deleted successfully.');
    }
}
