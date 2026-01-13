<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AchTransaction;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's companies
        $userCompanies = Company::where('created_by', $user->id)->pluck('id');
        
        // Statistics - only for user's companies
        $stats = [
            'total_companies' => Company::where('created_by', $user->id)->count(),
            'total_employees' => Employee::whereIn('company_id', $userCompanies)
                ->where('is_active', true)
                ->count(),
            'payroll_runs_month' => PayrollRun::whereIn('company_id', $userCompanies)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_processed' => PayrollRun::whereIn('company_id', $userCompanies)
                ->where('status', 'finalized')
                ->sum('total_net'),
        ];

        // Chart data - Last 6 months payroll
        $months = [];
        $amounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $months[] = $date->format('M Y');
            
            // Get payroll runs for this month (by pay_period_end or created_at)
            $amount = PayrollRun::whereIn('company_id', $userCompanies)
                ->where('status', 'finalized')
                ->whereBetween('pay_period_end', [$monthStart, $monthEnd])
                ->sum('total_net');
            
            // If no data by pay_period_end, try created_at
            if ($amount == 0) {
                $amount = PayrollRun::whereIn('company_id', $userCompanies)
                    ->where('status', 'finalized')
                    ->whereMonth('created_at', $date->month)
                    ->whereYear('created_at', $date->year)
                    ->sum('total_net');
            }
            
            $amounts[] = (float) $amount;
        }

        $chart_data = [
            'months' => $months,
            'amounts' => $amounts
        ];

        // Status distribution
        $statusCounts = PayrollRun::whereIn('company_id', $userCompanies)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $status_data = [
            'labels' => ['Finalized', 'Approved', 'Preview', 'Draft'],
            'values' => [
                $statusCounts['finalized'] ?? 0,
                $statusCounts['approved'] ?? 0,
                $statusCounts['preview'] ?? 0,
                $statusCounts['draft'] ?? 0,
            ]
        ];

        // Recent payroll runs
        $recent_payrolls = PayrollRun::whereIn('company_id', $userCompanies)
            ->with('company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent ACH transactions
        $recent_ach = AchTransaction::whereHas('payrollRun', function($query) use ($userCompanies) {
                $query->whereIn('company_id', $userCompanies);
            })
            ->with('payrollRun')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('client.dashboard', compact(
            'stats',
            'chart_data',
            'status_data',
            'recent_payrolls',
            'recent_ach'
        ));
    }
}
