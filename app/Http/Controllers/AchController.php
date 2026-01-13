<?php

namespace App\Http\Controllers;

use App\Models\AchTransaction;
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

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $transactions = AchTransaction::with(['payrollRun', 'bankAccount'])->get();
        } else {
            $transactions = AchTransaction::whereHas('payrollRun', function ($query) use ($user) {
                if ($user->hasRole('client')) {
                    $companyIds = \App\Models\Company::where('created_by', $user->id)->pluck('id');
                    $query->whereIn('company_id', $companyIds);
                } else {
                    $employee = \App\Models\Employee::where('user_id', $user->id)->first();
                    if ($employee) {
                        $query->where('company_id', $employee->company_id);
                    }
                }
            })->with(['payrollRun', 'bankAccount'])->get();
        }

        return response()->json($transactions);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $transaction = AchTransaction::with(['payrollRun', 'bankAccount'])->findOrFail($id);
        
        return response()->json($transaction);
    }

    /**
     * Process ACH for a payroll run.
     */
    public function processPayroll(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'finalized') {
            return response()->json(['message' => 'Can only process ACH for finalized payroll runs'], 400);
        }

        $transactions = $this->achService->processPayrollAch($payrollRun);

        return response()->json([
            'message' => 'ACH processing initiated',
            'transactions' => $transactions,
        ]);
    }
}
