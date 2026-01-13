<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayrollRun;
use App\Services\PayrollService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PayrollController extends Controller
{
    protected $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->payrollService = $payrollService;
    }

    public function index()
    {
        $payrollRuns = PayrollRun::with(['company', 'creator'])
            ->latest()
            ->paginate(15);
        return view('admin.payroll.index', compact('payrollRuns'));
    }

    public function create()
    {
        $companies = Company::all();
        return view('admin.payroll.create', compact('companies'));
    }

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
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payrollRun = PayrollRun::create([
            ...$request->all(),
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('admin.payroll.show', $payrollRun)
            ->with('success', 'Payroll run created successfully!');
    }

    public function show(PayrollRun $payroll)
    {
        $payroll->load([
            'company',
            'creator',
            'approver',
            'payrollItems.employee',
            'payrollItems.deductions',
            'achTransactions'
        ]);
        return view('admin.payroll.show', compact('payroll'));
    }

    public function edit(PayrollRun $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->route('admin.payroll.show', $payroll)
                ->with('error', 'Can only edit draft payroll runs');
        }
        $companies = Company::all();
        return view('admin.payroll.edit', compact('payroll', 'companies'));
    }

    public function update(Request $request, PayrollRun $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only update draft payroll runs');
        }

        $validator = Validator::make($request->all(), [
            'pay_period_type' => 'sometimes|required|in:weekly,biweekly,semimonthly,monthly',
            'pay_period_start' => 'sometimes|required|date',
            'pay_period_end' => 'sometimes|required|date|after:pay_period_start',
            'pay_date' => 'sometimes|required|date|after_or_equal:pay_period_end',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $payroll->update($request->all());

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll run updated successfully!');
    }

    public function destroy(PayrollRun $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only delete draft payroll runs');
        }

        $payroll->delete();
        return redirect()->route('admin.payroll.index')
            ->with('success', 'Payroll run deleted successfully!');
    }

    public function calculate(PayrollRun $payroll)
    {
        if ($payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only calculate draft payroll runs');
        }

        $this->payrollService->calculatePayroll($payroll);

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll calculated successfully!');
    }

    public function approve(PayrollRun $payroll)
    {
        if ($payroll->status !== 'preview') {
            return redirect()->back()->with('error', 'Can only approve preview payroll runs');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll run approved successfully!');
    }

    public function finalize(PayrollRun $payroll)
    {
        if ($payroll->status !== 'approved') {
            return redirect()->back()->with('error', 'Can only finalize approved payroll runs');
        }

        $payroll->update([
            'status' => 'finalized',
            'finalized_at' => now(),
        ]);

        return redirect()->route('admin.payroll.show', $payroll)
            ->with('success', 'Payroll run finalized successfully!');
    }
}
