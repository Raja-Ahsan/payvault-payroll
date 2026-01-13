<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
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

    public function index()
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        $payrollRuns = PayrollRun::whereIn('company_id', $userCompanyIds)
            ->with(['company'])
            ->latest()
            ->paginate(15);
        return view('client.payroll.index', compact('payrollRuns'));
    }

    public function create()
    {
        $companies = Company::where('created_by', Auth::id())->get();
        return view('client.payroll.create', compact('companies'));
    }

    public function store(Request $request)
    {
        // Ensure company belongs to user
        $company = Company::where('id', $request->company_id)
            ->where('created_by', Auth::id())
            ->firstOrFail();

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
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('client.payroll.show', $payrollRun)
            ->with('success', 'Payroll run created successfully!');
    }

    public function show(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        $payroll->load([
            'company',
            'payrollItems.employee',
            'payrollItems.deductions',
            'achTransactions'
        ]);
        return view('client.payroll.show', compact('payroll'));
    }

    public function edit(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payroll->status !== 'draft') {
            return redirect()->route('client.payroll.show', $payroll)
                ->with('error', 'Can only edit draft payroll runs');
        }
        $companies = Company::where('created_by', Auth::id())->get();
        return view('client.payroll.edit', compact('payroll', 'companies'));
    }

    public function update(Request $request, PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

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

        return redirect()->route('client.payroll.show', $payroll)
            ->with('success', 'Payroll run updated successfully!');
    }

    public function destroy(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only delete draft payroll runs');
        }

        $payroll->delete();
        return redirect()->route('client.payroll.index')
            ->with('success', 'Payroll run deleted successfully!');
    }

    public function calculate(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payroll->status !== 'draft') {
            return redirect()->back()->with('error', 'Can only calculate draft payroll runs');
        }

        $this->payrollService->calculatePayroll($payroll);

        return redirect()->route('client.payroll.show', $payroll)
            ->with('success', 'Payroll calculated successfully!');
    }

    public function approve(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payroll->status !== 'preview') {
            return redirect()->back()->with('error', 'Can only approve preview payroll runs');
        }

        $payroll->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('client.payroll.show', $payroll)
            ->with('success', 'Payroll run approved successfully!');
    }

    public function finalize(PayrollRun $payroll)
    {
        // Ensure payroll belongs to user's company
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        if (!in_array($payroll->company_id, $userCompanyIds->toArray())) {
            abort(403, 'Unauthorized access.');
        }

        if ($payroll->status !== 'approved') {
            return redirect()->back()->with('error', 'Can only finalize approved payroll runs');
        }

        $payroll->update([
            'status' => 'finalized',
            'finalized_at' => now(),
        ]);

        return redirect()->route('client.payroll.show', $payroll)
            ->with('success', 'Payroll run finalized successfully!');
    }
}
