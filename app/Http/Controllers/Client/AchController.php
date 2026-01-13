<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\AchTransaction;
use App\Models\Company;
use App\Models\PayrollRun;
use App\Services\AchService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AchController extends Controller
{
    protected $achService;

    public function __construct(AchService $achService)
    {
        $this->achService = $achService;
    }

    public function index()
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        
        $transactions = AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                $query->whereIn('company_id', $userCompanyIds);
            })
            ->with(['payrollRun', 'bankAccount'])
            ->latest()
            ->paginate(15);
        
        $stats = [
            'total' => AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                    $query->whereIn('company_id', $userCompanyIds);
                })->count(),
            'completed' => AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                    $query->whereIn('company_id', $userCompanyIds);
                })->where('status', 'completed')->count(),
            'processing' => AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                    $query->whereIn('company_id', $userCompanyIds);
                })->where('status', 'processing')->count(),
            'failed' => AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                    $query->whereIn('company_id', $userCompanyIds);
                })->where('status', 'failed')->count(),
            'total_amount' => AchTransaction::whereHas('payrollRun', function($query) use ($userCompanyIds) {
                    $query->whereIn('company_id', $userCompanyIds);
                })->where('status', 'completed')->sum('amount'),
        ];

        return view('client.ach.index', compact('transactions', 'stats'));
    }

    public function processPayroll(PayrollRun $payrollRun)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payrollRun->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payrollRun->status !== 'finalized') {
            return redirect()->back()->with('error', 'Can only process ACH for finalized payroll runs');
        }

        $transactions = $this->achService->processPayrollAch($payrollRun);
        $count = count($transactions);

        if ($count > 0) {
            return redirect()->route('client.ach.index')
                ->with('success', "ACH processing initiated successfully! {$count} transaction(s) created.");
        } else {
            return redirect()->back()
                ->with('warning', 'No ACH transactions created. Employees may not have verified bank accounts.');
        }
    }
}
