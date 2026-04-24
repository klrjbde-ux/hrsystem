<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PerformanceReviewController extends Controller
{
   public function index(Request $request)
{
    // Define the query builder
    $query = PerformanceReview::with(['employee', 'reviewer'])
        ->orderBy('period_end', 'desc')
        ->orderBy('created_at', 'desc');

    // Apply filters
    if ($request->filled('employee_name')) {
        $query->whereHas('employee', function ($q) use ($request) {
            $q->where('firstname', 'like', $request->employee_name . '%');
        });
    }

    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    if ($request->filled('period_from')) {
        $query->whereDate('period_start', '>=', $request->period_from);
    }

    if ($request->filled('period_to')) {
        $query->whereDate('period_end', '<=', $request->period_to);
    }

    // Execute the query
    $reviews = $query->get();

    return view('performance.reviews.index', compact('reviews'));
}

    public function create()
    {
        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();
        $reviewers = Employee::orderBy('firstname')->orderBy('lastname')->get();
        return view('performance.reviews.create', compact('employees', 'reviewers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'reviewer_id' => 'nullable|exists:employees,id',
            'overall_rating' => 'nullable|numeric|min:0|max:10',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'comments' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $pendingReview = PerformanceReview::where('employee_id', $request->employee_id)
            ->whereIn('status', ['draft', 'in_progress'])
            ->exists();
        if ($pendingReview) {
            return redirect()->back()
                ->with('danger', 'This employee already has a review in progress. Please complete or approve it before adding a new one.')
                ->withInput();
        }
        try {
            $review = PerformanceReview::create([
                'employee_id' => $request->employee_id,
                'reviewer_id' => $request->reviewer_id,
                'period_start' => $request->period_start,
                'period_end' => $request->period_end,
                'overall_rating' => $request->overall_rating,
                'strengths' => $request->strengths,
                'improvements' => $request->improvements,
                'comments' => $request->comments,
                'status' => $request->status ?? 'draft',
            ]);

        } catch (\Exception $e) {

            return redirect()->back()->with('danger', 'Error creating performance review.');
        }
        return redirect()->route('performance.reviews.index')
            ->with('success', 'Performance review created successfully.');
    }
    public function show($id)
    {
        $review = PerformanceReview::with(['employee', 'reviewer', 'appraisals'])
            ->findOrFail($id);
        return view('performance.reviews.show', compact('review'));
    }
    public function edit($id)
    {
        $review = PerformanceReview::findOrFail($id);
        $employees = Employee::orderBy('firstname')->orderBy('lastname')->get();
        $reviewers = Employee::orderBy('firstname')->orderBy('lastname')->get();

        return view('performance.reviews.edit', compact('review', 'employees', 'reviewers'));
    }

    public function update(Request $request, $id)
    {
        $review = PerformanceReview::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'reviewer_id' => 'nullable|exists:employees,id',
            'overall_rating' => 'nullable|numeric|min:0|max:10',
            'strengths' => 'nullable|string',
            'improvements' => 'nullable|string',
            'comments' => 'nullable|string',
            'status' => 'required|in:draft,in_progress,completed',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        try {
            $review->update($request->only([
                'employee_id',
                'reviewer_id',
                'period_start',
                'period_end',
                'overall_rating',
                'strengths',
                'improvements',
                'comments',
                'status',
            ]));
        } catch (\Exception $e) {
            return redirect()->back()->with('danger', 'Error updating performance review.');
        }
        return redirect()->route('performance.reviews.index')
            ->with('success', 'Performance review updated successfully.');
    }
    public function destroy($id)
    {
        $review = PerformanceReview::findOrFail($id);
        $review->delete();
        return redirect()->route('performance.reviews.index')
            ->with('success', 'Performance review deleted successfully.');
    }
}
