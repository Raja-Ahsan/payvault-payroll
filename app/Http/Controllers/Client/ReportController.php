<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');

        // Payroll summary by company
        $companyPayrolls = PayrollRun::whereIn('company_id', $userCompanyIds)
            ->select('company_id', DB::raw('SUM(total_net) as total'), DB::raw('COUNT(*) as count'))
            ->where('status', 'finalized')
            ->groupBy('company_id')
            ->with('company')
            ->get();

        // Monthly payroll trends
        $monthlyData = PayrollRun::whereIn('company_id', $userCompanyIds)
            ->select(
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
        $statusBreakdown = PayrollRun::whereIn('company_id', $userCompanyIds)
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        return view('client.reports.index', compact('companyPayrolls', 'monthlyData', 'statusBreakdown'));
    }
}
