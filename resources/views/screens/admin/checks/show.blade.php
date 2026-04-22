@extends('layouts.admin.master')
@section('title', 'Check preview')
@section('content')
@php
    use App\Services\Payroll\PayrollCheckCalculator;

    $e = $check->employee;
    $employeeFullName = trim(implode(' ', array_filter([
        $e->first_name ?? '',
        $e->middle_name ?? '',
        $e->last_name ?? '',
    ])));
    if ($employeeFullName === '') {
        $employeeFullName = '—';
    }

    $preview = $check->check_preview;
    $incomeSlots = PayrollCheckCalculator::INCOME_SLOTS;
    $incomeTitles = [
        'regular_hourly' => 'Regular hourly pay',
        'overtime_hourly' => 'Overtime hourly pay',
        'yearly_salary' => 'Yearly salary',
        'double_time' => 'Double-time',
    ];
    $payTypeLabel = static function (?string $t): string {
        return $t === 'per_year' ? 'Per year' : 'Per hour';
    };
    $payFrequencyLabel = match ($check->pay_frequency) {
        'daily_260' => 'Daily (260 pay periods)',
        'weekly_52' => 'Weekly (52 Pay Periods)',
        'biweekly_26' => 'Bi-Weekly (26 Pay Periods)',
        'semimonthly_24' => 'Semi-Monthly (24 Pay Periods)',
        'monthly_12' => 'Monthly (12 Pay Periods)',
        'quarterly_4' => 'Quarterly (4 pay periods)',
        'annual_1' => 'Annual (1 pay period)',
        default => $check->pay_frequency,
    };
    $money = static fn ($v) => '$' . number_format((float) $v, 2);
    $rateMoney = static fn ($v) => '$' . number_format((float) $v, 4);

    $empTaxDefs = [
        ['k' => 'federal_income', 'l' => 'Federal income tax'],
        ['k' => 'social_security', 'l' => 'Social security (employee)'],
        ['k' => 'medicare', 'l' => 'Medicare (employee)'],
        ['k' => 'state_income', 'l' => 'State income tax'],
        ['k' => 'local_income', 'l' => 'Local income tax'],
        ['k' => 'state_disability', 'l' => 'State disability insurance (employee)'],
    ];
    $erTaxDefs = [
        ['k' => 'social_security', 'l' => 'Social security (employer)'],
        ['k' => 'medicare', 'l' => 'Medicare (employer)'],
        ['k' => 'federal_unemployment', 'l' => 'Fed unemployment (employer)'],
        ['k' => 'state_unemployment', 'l' => 'State unemployment (employer)'],
        ['k' => 'state_disability', 'l' => 'State disability insurance (employer)'],
    ];
@endphp
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card mb-3">
                    <div class="card-header card-no-border d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <h2 class="form-heading mb-0">
                            Check # [{{ $check->check_number }}]
                        </h2>
                        <div class="d-flex gap-2">
                            <a href="{{ route('checks.create') }}" class="btn btn-primary btn-sm f-w-500">New check</a>
                            <a href="{{ route('checks.index') }}" class="btn btn-outline-secondary btn-sm f-w-500">All checks</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-8">
                                <p class="mb-1 text-muted small">Employee</p>
                                <p class="mb-3 fs-5 f-w-600">{{ $employeeFullName }}</p>
                                <div class="row g-3">
                                    <div class="col-sm-6">
                                        <p class="mb-0 text-muted small">Pay period</p>
                                        <p class="mb-0 f-w-500">
                                            From:
                                            <strong>{{ $check->period_begin_date?->format('m/d/Y') ?? '—' }}</strong>
                                            &nbsp; To:
                                            <strong>{{ $check->period_end_date?->format('m/d/Y') ?? '—' }}</strong>
                                        </p>
                                    </div>
                                    <div class="col-sm-6">
                                        <p class="mb-0 text-muted small">Pay frequency</p>
                                        <p class="mb-0 f-w-500">{{ $payFrequencyLabel }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card border h-100">
                                    <div class="card-body text-center py-4">
                                        <p class="text-muted small mb-1">Net pay</p>
                                        <p class="display-6 mb-0 f-w-700">{{ $money(data_get($preview, 'summary.this_check.net_pay', $check->net_pay)) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header card-no-border">
                        <h2 class="form-heading mb-0">Income details</h2>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Title</span></th>
                                        <th style="width: 110px;"><span class="c-o-light f-w-600">Type</span></th>
                                        <th style="width: 110px;"><span class="c-o-light f-w-600">Rate</span></th>
                                        <th style="width: 100px;"><span class="c-o-light f-w-600">Qty</span></th>
                                        <th style="width: 110px;"><span class="c-o-light f-w-600">Amount</span></th>
                                        <th style="width: 110px;"><span class="c-o-light f-w-600">YTD</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($incomeSlots as $slot)
                                        @php
                                            $row = data_get($preview, 'income.' . $slot);
                                            $br = $check->income_breakdown[$slot] ?? [];
                                            $rate = data_get($row, 'rate', data_get($br, 'rate', 0));
                                            $qty = data_get($row, 'quantity', data_get($br, 'quantity', ''));
                                            $ptype = data_get($row, 'pay_type', data_get($br, 'pay_type', 'per_hour'));
                                            $amt = data_get($row, 'amount', data_get($br, 'amount', 0));
                                            $ytd = data_get($row, 'ytd', $amt);
                                            $qtyDisp = $ptype === 'per_year'
                                                ? '—'
                                                : number_format((float) ($qty === '' || $qty === null ? 0 : $qty), 4);
                                        @endphp
                                        <tr>
                                            <td>{{ $incomeTitles[$slot] ?? $slot }}</td>
                                            <td>{{ $payTypeLabel($ptype) }}</td>
                                            <td>{{ $rateMoney($rate) }}</td>
                                            <td>{{ $qtyDisp }}</td>
                                            <td>{{ $money($amt) }}</td>
                                            <td>{{ $money($ytd) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header card-no-border">
                        <h2 class="form-heading mb-0">Tax details</h2>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Title</span></th>
                                        <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                        <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($empTaxDefs as $def)
                                        @php
                                            $cell = ($preview !== null && isset($preview['taxes']['employee'][$def['k']]))
                                                ? $preview['taxes']['employee'][$def['k']]
                                                : null;
                                            if ($cell) {
                                                $a = $cell['amount'];
                                                $y = $cell['ytd'];
                                            } else {
                                                $a = match ($def['k']) {
                                                    'federal_income' => $check->employee_federal_income_tax,
                                                    'social_security' => $check->employee_social_security,
                                                    'medicare' => $check->employee_medicare,
                                                    'state_income' => $check->employee_state_income_tax,
                                                    'local_income' => $check->employee_local_income_tax,
                                                    'state_disability' => $check->employee_state_disability,
                                                    default => 0,
                                                };
                                                $y = $a;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $def['l'] }}</td>
                                            <td>{{ $money($a) }}</td>
                                            <td>{{ $money($y) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="table-responsive custom-scrollbar">
                            <table class="table mb-0">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Title</span></th>
                                        <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                        <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($erTaxDefs as $def)
                                        @php
                                            $cell = ($preview !== null && isset($preview['taxes']['employer'][$def['k']]))
                                                ? $preview['taxes']['employer'][$def['k']]
                                                : null;
                                            if ($cell) {
                                                $a = $cell['amount'];
                                                $y = $cell['ytd'];
                                            } else {
                                                $a = match ($def['k']) {
                                                    'social_security' => $check->employer_social_security,
                                                    'medicare' => $check->employer_medicare,
                                                    'federal_unemployment' => $check->employer_federal_unemployment,
                                                    'state_unemployment' => $check->employer_state_unemployment,
                                                    'state_disability' => $check->employer_state_disability,
                                                    default => 0,
                                                };
                                                $y = $a;
                                            }
                                        @endphp
                                        <tr>
                                            <td>{{ $def['l'] }}</td>
                                            <td>{{ $money($a) }}</td>
                                            <td>{{ $money($y) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Deduction details</h2>
                            </div>
                            <div class="card-body pt-0 px-0">
                                <div class="table-responsive custom-scrollbar">
                                    <table class="table mb-0">
                                        <thead>
                                            <tr>
                                                <th><span class="c-o-light f-w-600">Title</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">Amount</span></th>
                                                <th style="width: 120px;"><span class="c-o-light f-w-600">YTD</span></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php
                                                $d401 = ($preview !== null && isset($preview['deductions']['401k_employee']))
                                                    ? $preview['deductions']['401k_employee']
                                                    : null;
                                                $d401a = is_array($d401) ? ($d401['amount'] ?? $check->deduction_401k) : $check->deduction_401k;
                                                $d401y = is_array($d401) ? ($d401['ytd'] ?? $d401a) : $d401a;
                                            @endphp
                                            <tr>
                                                <td>401K (employee)</td>
                                                <td>{{ $money($d401a) }}</td>
                                                <td>{{ $money($d401y) }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card h-100">
                            <div class="card-header card-no-border">
                                <h2 class="form-heading mb-0">Check summary</h2>
                            </div>
                            <div class="card-body">
                                <p class="mb-1">Total incomes: <strong>{{ $money(data_get($preview, 'summary.this_check.total_incomes', $check->gross_total)) }}</strong></p>
                                <p class="mb-1">Total taxes (employee): <strong>{{ $money(data_get($preview, 'summary.this_check.total_taxes', $check->employee_taxes_total)) }}</strong></p>
                                <p class="mb-1">Total deductions: <strong>{{ $money(data_get($preview, 'summary.this_check.total_deductions', $check->total_deductions)) }}</strong></p>
                                <p class="mb-0">Net pay: <strong>{{ $money(data_get($preview, 'summary.this_check.net_pay', $check->net_pay)) }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($check->memo)
                    <div class="card mt-3">
                        <div class="card-body">
                            <span class="text-muted small">Memo</span>
                            <p class="mb-0">{{ $check->memo }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
