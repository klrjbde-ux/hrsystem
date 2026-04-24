<?php

namespace App\Http\Controllers\Performance;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Appraisal;
use App\Models\Employee;
use App\Models\PerformanceReview;
use Illuminate\Http\Request;

class PerformanceReportController extends Controller
{
    public function index()
    {
        try {
            $stats = [
                'total_reviews' => PerformanceReview::count(),
                'completed_reviews' => PerformanceReview::where('status', 'completed')->count(),
                'total_appraisals' => Appraisal::count(),
            ];
            $reviewsByStatus = PerformanceReview::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray();
            $avgRatings = DB::table('appraisals')
                ->whereNotNull('rating')
                ->selectRaw('employee_id, avg(rating) as avg_rating')
                ->groupBy('employee_id')
                ->orderByDesc('avg_rating')
                ->limit(10)
                ->get()
                ->map(function ($row) {
                    $emp = Employee::find($row->employee_id);
                    return (object) [
                        'employee' => $emp,
                        'avg_rating' => round($row->avg_rating, 1),
                    ];
                });

        } catch (\Exception $e) {

            return redirect()->back()->with('danger', 'Error loading performance report.');
        }

        return view('performance.reports.index', compact(
            'stats',
            'reviewsByStatus',
            'avgRatings'
        ));
    }
}
