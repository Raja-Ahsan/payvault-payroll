<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayrollItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PayrollRegisterController extends Controller
{
    /**
     * All payroll line items for employees under the client’s companies.
     */
    public function register()
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');

        $payrollItems = PayrollItem::query()
            ->whereHas('employee', function ($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with(['employee.company'])
            ->orderByDesc('pay_date')
            ->orderByDesc('id')
            ->paginate(20);

        return view('client.payroll.register', compact('payrollItems'));
    }

    public function requestPayroll(Request $request)
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');
        $payrollItems = null;
        $filtersApplied = false;

        if ($request->filled('from_date') || $request->filled('to_date')) {
            $validated = $request->validate([
                'from_date' => 'required|date',
                'to_date' => 'required|date|after_or_equal:from_date',
            ], [
                'from_date.required' => 'Please choose a from date.',
                'to_date.required' => 'Please choose a to date.',
                'to_date.after_or_equal' => 'The to date must be on or after the from date.',
            ]);

            $filtersApplied = true;

            $payrollItems = PayrollItem::query()
                ->whereHas('employee', function ($q) use ($userCompanyIds) {
                    $q->whereIn('company_id', $userCompanyIds);
                })
                ->with(['employee.company'])
                ->whereBetween('pay_date', [$validated['from_date'], $validated['to_date']])
                ->orderByDesc('pay_date')
                ->orderByDesc('id')
                ->paginate(20)
                ->appends($request->only(['from_date', 'to_date']));
        }

        return view('client.payroll.request', compact('payrollItems', 'filtersApplied'));
    }
}
