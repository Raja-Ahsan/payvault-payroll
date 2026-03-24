{{--
    Paycheck / paystub body — single source for screen + PDF.
    Amounts come from $payrollItem (stored values); totals are summed for display check.
--}}
@php
    $employeeLabel = $employee->name
        ?: trim(collect([$employee->first_name ?? null, $employee->last_name ?? null])->filter()->join(' '));
    $companyName = $employee->company->name ?? 'DIY Payroll Solutions';
    $gross = (float) $payrollItem->gross_pay;
    $d401 = (float) $payrollItem->k401_amount;
    $fed = (float) $payrollItem->fed_tax;
    $state = (float) $payrollItem->state_tax;
    $local = (float) $payrollItem->local_tax;
    $ss = (float) $payrollItem->social_security;
    $medi = (float) $payrollItem->medi_care;
    $ins = (float) $payrollItem->insurance_deduction;
    $other = (float) $payrollItem->other_deductions;
    $totalDeductions = round($d401 + $fed + $state + $local + $ss + $medi + $ins + $other, 2);
    $netPay = (float) $payrollItem->net_pay;
    $payDateStr = $payrollItem->pay_date ? \Illuminate\Support\Carbon::parse($payrollItem->pay_date)->format('M j, Y') : '—';
    $expected401 = round($gross * ($employee->effective401kContributionPercent() / 100), 2);
    $k401Mismatch = $gross > 0.01 && abs($d401 - $expected401) > 0.02;
@endphp

<div class="paystub-wrap" id="paystub-print-area">
    <div class="paystub-header">
        <div>
            <h1 class="paystub-brand">{{ $companyName }}</h1>
            <p class="paystub-sub">Earnings statement / Paystub</p>
        </div>
        <div class="paystub-meta">
            <div><span class="lbl">Pay date</span><span class="val">{{ $payDateStr }}</span></div>
            <div><span class="lbl">Pay period</span><span class="val">{{ $payrollItem->pay_period ?? '—' }}</span></div>
            <div><span class="lbl">Record #</span><span class="val">{{ $payrollItem->id }}</span></div>
        </div>
    </div>

    <div class="paystub-employee-box">
        <div class="pe-col">
            <span class="lbl">Employee</span>
            <span class="val strong">{{ $employeeLabel }}</span>
        </div>
        <div class="pe-col">
            <span class="lbl">Employee ID</span>
            <span class="val">{{ $employee->employee_id ?? '—' }}</span>
        </div>
        @if($employee->address ?? null)
            <div class="pe-col pe-wide">
                <span class="lbl">Address</span>
                <span class="val">{{ $employee->address }}</span>
            </div>
        @endif
    </div>

    @if($k401Mismatch)
        <div class="paystub-warning" style="margin-bottom:1rem;padding:0.75rem 1rem;background:#fffbeb;border:1px solid #fbbf24;border-radius:6px;font-size:0.8125rem;color:#92400e;">
            <strong>401(k) note:</strong> Stored amount (${{ number_format($d401, 2) }}) does not match the current plan rate ({{ number_format($employee->effective401kContributionPercent(), 2) }}% of gross = ${{ number_format($expected401, 2) }}). Old payroll rows may have been saved before the rate was fixed. New payrolls use: gross × (plan % ÷ 100).
        </div>
    @endif

    <table class="paystub-columns" width="100%" cellpadding="0" cellspacing="0" role="presentation">
        <tr>
            <td class="paystub-col-cell" valign="top" width="50%">
                <section class="paystub-panel">
                    <h2 class="panel-title">Hours &amp; earnings</h2>
                    <table class="paystub-table">
                        <tbody>
                            <tr><td>Regular hours</td><td class="num">{{ number_format((float) $payrollItem->regular_hours, 2) }}</td></tr>
                            <tr><td>Vacation hours</td><td class="num">{{ number_format((float) $payrollItem->vacation_hours, 2) }}</td></tr>
                            <tr><td>Sick hours</td><td class="num">{{ number_format((float) $payrollItem->sick_hours, 2) }}</td></tr>
                            <tr><td>Holiday hours</td><td class="num">{{ number_format((float) $payrollItem->holidays_hours, 2) }}</td></tr>
                            <tr><td>Personal hours</td><td class="num">{{ number_format((float) $payrollItem->personal_hours, 2) }}</td></tr>
                            <tr><td>Overtime hours</td><td class="num">{{ number_format((float) $payrollItem->overtime_hours, 2) }}</td></tr>
                        </tbody>
                    </table>
                    <div class="gross-line">
                        <span>Gross pay</span>
                        <span class="gross-amt">${{ number_format($gross, 2) }}</span>
                    </div>
                </section>
            </td>
            <td class="paystub-col-gap" width="16"></td>
            <td class="paystub-col-cell" valign="top" width="50%">
                <section class="paystub-panel">
                    <h2 class="panel-title">Deductions (employee)</h2>
                    <table class="paystub-table">
                        <thead>
                            <tr><th>Description</th><th class="num">Amount</th></tr>
                        </thead>
                        <tbody>
                            <tr><td>401(k)</td><td class="num">${{ number_format($d401, 2) }}</td></tr>
                            <tr><td>Federal tax</td><td class="num">${{ number_format($fed, 2) }}</td></tr>
                            <tr><td>State tax</td><td class="num">${{ number_format($state, 2) }}</td></tr>
                            <tr><td>Local tax</td><td class="num">${{ number_format($local, 2) }}</td></tr>
                            <tr><td>Social Security</td><td class="num">${{ number_format($ss, 2) }}</td></tr>
                            <tr><td>Medicare</td><td class="num">${{ number_format($medi, 2) }}</td></tr>
                            <tr><td>Insurance</td><td class="num">${{ number_format($ins, 2) }}</td></tr>
                            <tr><td>Other</td><td class="num">${{ number_format($other, 2) }}</td></tr>
                        </tbody>
                    </table>
                    <div class="subtotal-line">
                        <span>Total deductions</span>
                        <span>${{ number_format($totalDeductions, 2) }}</span>
                    </div>
                </section>
            </td>
        </tr>
    </table>

    <div class="net-pay-box">
        <div class="net-inner">
            <span class="net-label">Net pay</span>
            <span class="net-amount">${{ number_format($netPay, 2) }}</span>
        </div>
        <p class="net-note">Amount after taxes and deductions for this pay period.</p>
    </div>

    <footer class="paystub-footer">
        <p>DIY Payroll Solutions</p>
    </footer>
</div>
