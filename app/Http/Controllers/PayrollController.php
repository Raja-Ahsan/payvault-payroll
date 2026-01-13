<?php

namespace App\Http\Controllers;

use App\Models\PayrollRun;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->hasRole('admin')) {
            $payrollRuns = PayrollRun::with(['company', 'creator'])->get();
        } elseif ($user->hasRole('client')) {
            $companyIds = \App\Models\Company::where('created_by', $user->id)->pluck('id');
            $payrollRuns = PayrollRun::whereIn('company_id', $companyIds)
                ->with(['company', 'creator'])
                ->get();
        } else {
            $employee = \App\Models\Employee::where('user_id', $user->id)->first();
            if ($employee) {
                $payrollRuns = PayrollRun::where('company_id', $employee->company_id)
                    ->with(['company', 'creator'])
                    ->get();
            } else {
                $payrollRuns = collect();
            }
        }

        return response()->json($payrollRuns);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,id',
            'pay_period_type' => 'required|in:weekly,biweekly,semimonthly,monthly',
            'pay_period_start' => 'required|date',
            'pay_period_end' => 'required|date|after:pay_period_start',
            'pay_date' => 'required|date|after_or_equal:pay_period_end',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payrollRun = PayrollRun::create([
            ...$request->all(),
            'status' => 'draft',
            'created_by' => Auth::id(),
        ]);

        return response()->json($payrollRun->load(['company', 'creator']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payrollRun = PayrollRun::with([
            'company',
            'creator',
            'approver',
            'payrollItems.employee',
            'payrollItems.deductions',
            'achTransactions'
        ])->findOrFail($id);
        
        return response()->json($payrollRun);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $payrollRun = PayrollRun::findOrFail($id);
        
        if ($payrollRun->status !== 'draft') {
            return response()->json(['message' => 'Can only update draft payroll runs'], 400);
        }

        $validator = Validator::make($request->all(), [
            'pay_period_type' => 'sometimes|required|in:weekly,biweekly,semimonthly,monthly',
            'pay_period_start' => 'sometimes|required|date',
            'pay_period_end' => 'sometimes|required|date|after:pay_period_start',
            'pay_date' => 'sometimes|required|date|after_or_equal:pay_period_end',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $payrollRun->update($request->all());

        return response()->json($payrollRun->load(['company', 'creator']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payrollRun = PayrollRun::findOrFail($id);
        
        if ($payrollRun->status !== 'draft') {
            return response()->json(['message' => 'Can only delete draft payroll runs'], 400);
        }

        $payrollRun->delete();

        return response()->json(['message' => 'Payroll run deleted successfully']);
    }

    /**
     * Calculate payroll for a payroll run.
     */
    public function calculate(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'draft') {
            return response()->json(['message' => 'Can only calculate draft payroll runs'], 400);
        }

        $result = $this->payrollService->calculatePayroll($payrollRun);

        return response()->json([
            'message' => 'Payroll calculated successfully',
            'payroll_run' => $payrollRun->load(['payrollItems.employee']),
            'summary' => $result,
        ]);
    }

    /**
     * Approve a payroll run.
     */
    public function approve(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'preview') {
            return response()->json(['message' => 'Can only approve preview payroll runs'], 400);
        }

        $payrollRun->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll run approved successfully',
            'payroll_run' => $payrollRun->load(['approver']),
        ]);
    }

    /**
     * Finalize a payroll run.
     */
    public function finalize(PayrollRun $payrollRun)
    {
        if ($payrollRun->status !== 'approved') {
            return response()->json(['message' => 'Can only finalize approved payroll runs'], 400);
        }

        $payrollRun->update([
            'status' => 'finalized',
            'finalized_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payroll run finalized successfully',
            'payroll_run' => $payrollRun,
        ]);
    }
}
