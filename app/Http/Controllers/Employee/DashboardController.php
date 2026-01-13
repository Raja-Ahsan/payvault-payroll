<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\AchTransaction;
use App\Models\Employee;
use App\Models\PayrollItem;
use App\Models\PayrollRun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get employee record for this user
        $employee = Employee::where('user_id', $user->id)
            ->with(['company', 'bankAccounts'])
            ->first();
        
        if (!$employee) {
            // Try to find employee by email as fallback
            $employee = Employee::where('email', $user->email)
                ->with(['company', 'bankAccounts'])
                ->first();
            
            if ($employee && !$employee->user_id) {
                // Link the employee to this user
                $employee->update(['user_id' => $user->id]);
            }
        }
        
        if (!$employee) {
            return redirect()->route('web.dashboard')
                ->with('error', 'No employee record found. Please contact your administrator to link your account to an employee record.');
        }

        // Statistics
        $stats = [
            'total_payrolls' => PayrollItem::where('employee_id', $employee->id)
                ->whereHas('payrollRun', function($query) {
                    $query->where('status', 'finalized');
                })
                ->count(),
            'total_earnings' => PayrollItem::where('employee_id', $employee->id)
                ->whereHas('payrollRun', function($query) {
                    $query->where('status', 'finalized');
                })
                ->sum('net_pay'),
            'this_month_earnings' => PayrollItem::where('employee_id', $employee->id)
                ->whereHas('payrollRun', function($query) {
                    $query->where('status', 'finalized')
                        ->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year);
                })
                ->sum('net_pay'),
            'bank_accounts' => $employee->bankAccounts()->where('is_active', true)->count(),
        ];

        // Chart data - Last 6 months earnings
        $months = [];
        $amounts = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthStart = $date->copy()->startOfMonth();
            $monthEnd = $date->copy()->endOfMonth();
            
            $months[] = $date->format('M Y');
            
            $amount = PayrollItem::where('employee_id', $employee->id)
                ->whereHas('payrollRun', function($query) use ($monthStart, $monthEnd) {
                    $query->where('status', 'finalized')
                        ->whereBetween('pay_period_end', [$monthStart, $monthEnd]);
                })
                ->sum('net_pay');
            
            // If no data by pay_period_end, try created_at
            if ($amount == 0) {
                $amount = PayrollItem::where('employee_id', $employee->id)
                    ->whereHas('payrollRun', function($query) use ($date) {
                        $query->where('status', 'finalized')
                            ->whereMonth('created_at', $date->month)
                            ->whereYear('created_at', $date->year);
                    })
                    ->sum('net_pay');
            }
            
            $amounts[] = (float) $amount;
        }

        $chart_data = [
            'months' => $months,
            'amounts' => $amounts
        ];

        // Recent payroll items
        $recent_payrolls = PayrollItem::where('employee_id', $employee->id)
            ->with(['payrollRun' => function($query) {
                $query->with('company');
            }])
            ->whereHas('payrollRun', function($query) {
                $query->where('status', 'finalized');
            })
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Recent ACH transactions
        $recent_ach = AchTransaction::whereHas('payrollRun.payrollItems', function($query) use ($employee) {
                $query->where('employee_id', $employee->id);
            })
            ->with('payrollRun')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('employee.dashboard', compact(
            'employee',
            'stats',
            'chart_data',
            'recent_payrolls',
            'recent_ach'
        ));
    }
}
