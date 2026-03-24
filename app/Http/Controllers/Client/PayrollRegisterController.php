<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\PayrollItem;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
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
            ->paginate(15);

        return view('client.payroll.register', compact('payrollItems'));
    }

    public function requestPayroll(Request $request)
    {
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

            $payrollItems = $this->payrollItemsForRequestFilter($validated)
                ->paginate(15)
                ->appends($request->only(['from_date', 'to_date']));
        }

        return view('client.payroll.request', compact('payrollItems', 'filtersApplied'));
    }

    /**
     * PDF export for Request Payroll — same date range as the on-screen table (all rows, not paginated).
     */
    public function requestPayrollPdf(Request $request)
    {
        $validated = $request->validate([
            'from_date' => 'required|date',
            'to_date' => 'required|date|after_or_equal:from_date',
        ], [
            'from_date.required' => 'Please choose a from date.',
            'to_date.required' => 'Please choose a to date.',
            'to_date.after_or_equal' => 'The to date must be on or after the from date.',
        ]);

        $payrollItems = $this->payrollItemsForRequestFilter($validated)->get();

        $pdf = Pdf::loadView('client.payroll.request_pdf', [
            'payrollItems' => $payrollItems,
            'fromDate' => $validated['from_date'],
            'toDate' => $validated['to_date'],
        ])->setPaper('letter', 'landscape');

        $fromSlug = str_replace('-', '', $validated['from_date']);
        $toSlug = str_replace('-', '', $validated['to_date']);

        return $pdf->download("payroll-records-{$fromSlug}-to-{$toSlug}.pdf");
    }

    /**
     * @param  array{from_date: string, to_date: string}  $validated
     */
    private function payrollItemsForRequestFilter(array $validated): Builder
    {
        $userCompanyIds = Company::where('created_by', Auth::id())->pluck('id');

        $from = Carbon::parse($validated['from_date'])->toDateString();
        $to = Carbon::parse($validated['to_date'])->toDateString();

        return PayrollItem::query()
            ->whereHas('employee', function ($q) use ($userCompanyIds) {
                $q->whereIn('company_id', $userCompanyIds);
            })
            ->with([
                'employee.company',
                'employee.bankAccounts' => fn ($q) => $q->where('is_active', true)->orderByDesc('is_primary')->orderBy('id'),
            ])
            ->where(function ($query) use ($from, $to) {
                $query
                    ->where(function ($q) use ($from, $to) {
                        $q->whereNotNull('pay_date')
                            ->where('pay_date', '>=', $from)
                            ->where('pay_date', '<=', $to);
                    })
                    ->orWhere(function ($q) use ($from, $to) {
                        // Pay period like "2026-03-25 - 2026-04-05": overlap with [from, to] if period_start <= to AND period_end >= from
                        $q->whereNotNull('pay_period')
                            ->whereRaw(
                                "TRIM(SUBSTRING_INDEX(pay_period, ' - ', 1)) <= ?
                                 AND TRIM(SUBSTRING_INDEX(pay_period, ' - ', -1)) >= ?
                                 AND TRIM(SUBSTRING_INDEX(pay_period, ' - ', 1)) REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'
                                 AND TRIM(SUBSTRING_INDEX(pay_period, ' - ', -1)) REGEXP '^[0-9]{4}-[0-9]{2}-[0-9]{2}$'",
                                [$to, $from]
                            );
                    });
            })
            ->orderByDesc('pay_date')
            ->orderByDesc('id');
    }
}
