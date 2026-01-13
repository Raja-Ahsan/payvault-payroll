<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // Payroll summary by company
        $companyPayrolls = PayrollRun::select('company_id', DB::raw('SUM(total_net) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'finalized')
            ->groupBy('company_id')
            ->with('company')
            ->get();

        // Monthly payroll trends
        $monthlyData = PayrollRun::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_net) as total'),
                DB::raw('COUNT(*) as count')
            )
            ->where('status', 'finalized')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        // Status breakdown
        $statusBreakdown = PayrollRun::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.reports.index', compact('companyPayrolls', 'monthlyData', 'statusBreakdown'));
    }
}
