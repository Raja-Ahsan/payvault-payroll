<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AchTransaction;
use App\Models\Company;
use App\Models\Employee;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistics
        $stats = [
            'total_companies' => Company::count(),
            'total_employees' => Employee::where('is_active', true)->count(),
            'payroll_runs_month' => PayrollRun::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'total_processed' => PayrollRun::where('status', 'finalized')
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
            $amount = PayrollRun::where('status', 'finalized')
                ->whereBetween('pay_period_end', [$monthStart, $monthEnd])
                ->sum('total_net');
            
            // If no data by pay_period_end, try created_at
            if ($amount == 0) {
                $amount = PayrollRun::where('status', 'finalized')
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
        $statusCounts = PayrollRun::select('status', DB::raw('count(*) as count'))
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
        $recent_payrolls = PayrollRun::with('company')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent ACH transactions
        $recent_ach = AchTransaction::with('payrollRun')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'chart_data',
            'status_data',
            'recent_payrolls',
            'recent_ach'
        ));
    }
}
