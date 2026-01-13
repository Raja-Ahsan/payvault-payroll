<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AchTransaction;
use App\Models\PayrollRun;
use App\Services\AchService;
use Illuminate\Http\Request;

class AchController extends Controller
{
    protected $achService;

    public function __construct(AchService $achService)
    {
        $this->achService = $achService;
    }

    public function index()
    {
        $transactions = AchTransaction::with(['payrollRun', 'bankAccount'])
            ->latest()
            ->paginate(15);
        
        $stats = [
            'total' => AchTransaction::count(),
            'completed' => AchTransaction::where('status', 'completed')->count(),
            'processing' => AchTransaction::where('status', 'processing')->count(),
            'failed' => AchTransaction::where('status', 'failed')->count(),
            'total_amount' => AchTransaction::where('status', 'completed')->sum('amount'),
        ];

        return view('admin.ach.index', compact('transactions', 'stats'));
    }

    public function processPayroll(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'finalized') {
            return redirect()->back()->with('error', 'Can only process ACH for finalized payroll runs');
        }

        $transactions = $this->achService->processPayrollAch($payrollRun);
        $count = count($transactions);

        if ($count > 0) {
            return redirect()->route('admin.ach.index')
                ->with('success', "ACH processing initiated successfully! {$count} transaction(s) created.");
        } else {
            return redirect()->back()
                ->with('warning', 'No ACH transactions created. Employees may not have verified bank accounts.');
        }
    }
}
