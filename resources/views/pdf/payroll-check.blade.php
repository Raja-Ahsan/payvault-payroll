<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Payroll Check #{{ $check->check_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; line-height: 1.35; }
        .topbar { margin-bottom: 14px; }
        .title { font-size: 19px; font-weight: 700; margin: 0 0 2px; }
        .subtitle { font-size: 12px; color: #4b5563; margin: 0; }
        .meta { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .meta td { border: 1px solid #d1d5db; padding: 6px 8px; width: 25%; vertical-align: top; }
        .meta .k { font-size: 10px; color: #6b7280; margin-bottom: 1px; }
        .meta .v { font-size: 12px; font-weight: 600; }
        .section-title { margin: 16px 0 6px; font-size: 13px; font-weight: 700; }
        .grid { width: 100%; border-collapse: collapse; margin-top: 6px; }
        .grid th, .grid td { border: 1px solid #d1d5db; padding: 6px 7px; text-align: left; }
        .grid th { background: #f3f4f6; font-size: 10px; text-transform: uppercase; letter-spacing: .3px; }
        .right { text-align: right; }
        .center { text-align: center; }
        .muted { color: #6b7280; }
        .mb-0 { margin-bottom: 0; }
        .note { margin-top: 10px; font-size: 10px; color: #6b7280; }
        .two-col { width: 100%; border-collapse: separate; border-spacing: 12px 0; margin-left: -12px; }
        .two-col td { width: 50%; vertical-align: top; }
    </style>
</head>
<body>
@php
    $preview = $check->check_preview ?? [];
    $employee = $check->employee;
    $employeeName = trim(implode(' ', array_filter([
        $employee?->first_name ?? '',
        $employee?->middle_name ?? '',
        $employee?->last_name ?? '',
    ])));
    $employeeName = $employeeName !== '' ? $employeeName : 'Employee #'.$check->employee_id;
    $money = static fn ($v) => '$'.number_format((float) $v, 2);
    $rateMoney = static fn ($v) => '$'.number_format((float) $v, 4);
    $payTypeLabel = static fn (?string $t): string => $t === 'per_year' ? 'Per year' : 'Per hour';

    $incomeRows = is_array(data_get($preview, 'income')) ? data_get($preview, 'income') : (is_array($check->income_breakdown) ? $check->income_breakdown : []);
    $incomeTitles = [
        'regular_hourly' => 'Regular hourly pay',
        'overtime_hourly' => 'Overtime hourly pay',
    ];

    $empTaxDefs = [
        ['k' => 'federal_income', 'l' => 'Federal income tax', 'fallback' => $check->employee_federal_income_tax],
        ['k' => 'social_security', 'l' => 'Social security (employee)', 'fallback' => $check->employee_social_security],
        ['k' => 'medicare', 'l' => 'Medicare (employee)', 'fallback' => $check->employee_medicare],
        ['k' => 'state_income', 'l' => 'State income tax', 'fallback' => $check->employee_state_income_tax],
        ['k' => 'local_income', 'l' => 'Local income tax', 'fallback' => $check->employee_local_income_tax],
        ['k' => 'state_disability', 'l' => 'State disability insurance (employee)', 'fallback' => $check->employee_state_disability],
    ];
    $erTaxDefs = [
        ['k' => 'social_security', 'l' => 'Social security (employer)', 'fallback' => $check->employer_social_security],
        ['k' => 'medicare', 'l' => 'Medicare (employer)', 'fallback' => $check->employer_medicare],
        ['k' => 'federal_unemployment', 'l' => 'Fed unemployment (employer)', 'fallback' => $check->employer_federal_unemployment],
        ['k' => 'state_unemployment', 'l' => 'State unemployment (employer)', 'fallback' => $check->employer_state_unemployment],
        ['k' => 'state_disability', 'l' => 'State disability insurance (employer)', 'fallback' => $check->employer_state_disability],
    ];

    $leaveKeys = [
        'vacation_hours_earned' => 'Vac. hours earned',
        'vacation_hours_used' => 'Vac. hours used',
        'sick_hours_earned' => 'Sick hours earned',
        'sick_hours_used' => 'Sick hours used',
    ];
@endphp

    <div class="topbar">
        <p class="title">Payroll Check #{{ $check->check_number }}</p>
        <p class="subtitle mb-0">Detailed payroll statement</p>

        <table class="meta">
            <tr>
                <td>
                    <div class="k">Employee</div>
                    <div class="v">{{ $employeeName }}</div>
                </td>
                <td>
                    <div class="k">Pay date</div>
                    <div class="v">{{ optional($check->pay_date)->format('m/d/Y') ?? '—' }}</div>
                </td>
                <td>
                    <div class="k">Period begin</div>
                    <div class="v">{{ optional($check->period_begin_date)->format('m/d/Y') ?? '—' }}</div>
                </td>
                <td>
                    <div class="k">Period end</div>
                    <div class="v">{{ optional($check->period_end_date)->format('m/d/Y') ?? '—' }}</div>
                </td>
            </tr>
        </table>
    </div>

    <p class="section-title">Income Details</p>
    <table class="grid">
        <thead>
            <tr>
                <th>Title</th>
                <th>Type</th>
                <th>Rate</th>
                <th class="right">Qty</th>
                <th>Amount</th>
                <th>YTD</th>
            </tr>
        </thead>
        <tbody>
            @foreach (($incomeRows ?? []) as $slot => $row)
                @php
                    $ptype = (string) data_get($row, 'pay_type', 'per_hour');
                    $qty = data_get($row, 'quantity', '');
                    $qtyDisp = $ptype === 'per_year'
                        ? '—'
                        : number_format((float) ($qty === '' || $qty === null ? 0 : $qty), 4);
                @endphp
                <tr>
                    <td>{{ $incomeTitles[$slot] ?? str_replace('_', ' ', ucfirst((string) $slot)) }}</td>
                    <td>{{ $payTypeLabel($ptype) }}</td>
                    <td>{{ $rateMoney(data_get($row, 'rate', 0)) }}</td>
                    <td class="right">{{ $qtyDisp }}</td>
                    <td class="right">{{ $money(data_get($row, 'amount', 0)) }}</td>
                    <td class="right">{{ $money(data_get($row, 'ytd', data_get($row, 'amount', 0))) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <p class="section-title">Employee Tax Details</p>
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Tax</th>
                            <th>Amount</th>
                            <th>YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($empTaxDefs as $def)
                            @php
                                $cell = data_get($preview, 'taxes.employee.'.$def['k']);
                                $amt = is_array($cell) ? ($cell['amount'] ?? $def['fallback']) : $def['fallback'];
                                $ytd = is_array($cell) ? ($cell['ytd'] ?? $amt) : $amt;
                            @endphp
                            <tr>
                                <td>{{ $def['l'] }}</td>
                                <td class="right">{{ $money($amt) }}</td>
                                <td class="right">{{ $money($ytd) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
            <td>
                <p class="section-title">Employer Tax Details</p>
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Tax</th>
                            <th>Amount</th>
                            <th>YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($erTaxDefs as $def)
                            @php
                                $cell = data_get($preview, 'taxes.employer.'.$def['k']);
                                $amt = is_array($cell) ? ($cell['amount'] ?? $def['fallback']) : $def['fallback'];
                                $ytd = is_array($cell) ? ($cell['ytd'] ?? $amt) : $amt;
                            @endphp
                            <tr>
                                <td>{{ $def['l'] }}</td>
                                <td class="right">{{ $money($amt) }}</td>
                                <td class="right">{{ $money($ytd) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <p class="section-title">Deduction Details</p>
                @php
                    $d401 = data_get($preview, 'deductions.401k_employee');
                    $d401Amount = is_array($d401) ? ($d401['amount'] ?? $check->deduction_401k) : $check->deduction_401k;
                    $d401Ytd = is_array($d401) ? ($d401['ytd'] ?? $d401Amount) : $d401Amount;
                @endphp
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Deduction</th>
                            <th>Amount</th>
                            <th>YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>401K (employee)</td>
                            <td class="right">{{ $money($d401Amount) }}</td>
                            <td class="right">{{ $money($d401Ytd) }}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
            <td>
                <p class="section-title">Vacation / Sick Hours</p>
                <table class="grid">
                    <thead>
                        <tr>
                            <th>Description</th>
                            <th>Amount</th>
                            <th>YTD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($leaveKeys as $key => $label)
                            @php
                                $row = data_get($preview, 'leave.'.$key, []);
                            @endphp
                            <tr>
                                <td>{{ $label }}</td>
                                <td class="right">{{ number_format((float) data_get($row, 'amount', 0), 2) }}</td>
                                <td class="right">{{ number_format((float) data_get($row, 'ytd', data_get($row, 'amount', 0)), 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <p class="section-title">Check Summary</p>
    <table class="grid">
        <tbody>
            <tr>
                <th>Total incomes (this check)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.this_check.total_incomes', $check->gross_total)) }}</td>
                <th>Total incomes (YTD)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.ytd.total_incomes', data_get($preview, 'summary.this_check.total_incomes', $check->gross_total))) }}</td>
            </tr>
            <tr>
                <th>Total taxes (this check)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.this_check.total_taxes', $check->employee_taxes_total)) }}</td>
                <th>Total taxes (YTD)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.ytd.total_taxes', data_get($preview, 'summary.this_check.total_taxes', $check->employee_taxes_total))) }}</td>
            </tr>
            <tr>
                <th>Total deductions (this check)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.this_check.total_deductions', $check->total_deductions)) }}</td>
                <th>Total deductions (YTD)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.ytd.total_deductions', data_get($preview, 'summary.this_check.total_deductions', $check->total_deductions))) }}</td>
            </tr>
            <tr>
                <th>Net pay (this check)</th>
                <td class="right"><strong>{{ $money(data_get($preview, 'summary.this_check.net_pay', $check->net_pay)) }}</strong></td>
                <th>Net pay (YTD)</th>
                <td class="right">{{ $money(data_get($preview, 'summary.ytd.net_pay', data_get($preview, 'summary.this_check.net_pay', $check->net_pay))) }}</td>
            </tr>
        </tbody>
    </table>

    @if (!empty($check->memo))
        <p class="section-title">Memo</p>
        <table class="grid">
            <tbody>
                <tr>
                    <td>{{ $check->memo }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    <p class="note">DIY Payroll Solutions. Values are based on this payroll check</p>
</body>
</html>
