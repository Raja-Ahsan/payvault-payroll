{{-- DomPDF-safe layout: no nested tables inside table cells --}}
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
@endphp

<table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:12px;border-bottom:2px solid #1a5c24;">
    <tr>
        <td valign="top">
            <div style="font-size:16px;font-weight:bold;">{{ $companyName }}</div>
            <div style="font-size:10px;color:#555;">Earnings statement / Paystub</div>
        </td>
        <td valign="top" align="right" style="font-size:10px;">
            <div><strong>Pay date</strong> {{ $payDateStr }}</div>
            <div><strong>Pay period</strong> {{ $payrollItem->pay_period ?? '—' }}</div>
            <div><strong>Record #</strong> {{ $payrollItem->id }}</div>
        </td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="6" style="margin-bottom:12px;background:#f5f5f5;border:1px solid #ccc;">
    <tr>
        <td width="50%" valign="top">
            <div style="font-size:9px;color:#666;">Employee</div>
            <div style="font-weight:bold;">{{ $employeeLabel }}</div>
        </td>
        <td width="50%" valign="top">
            <div style="font-size:9px;color:#666;">Employee ID</div>
            <div>{{ $employee->employee_id ?? '—' }}</div>
        </td>
    </tr>
    @if($employee->address ?? null)
        <tr>
            <td colspan="2" valign="top">
                <div style="font-size:9px;color:#666;">Address</div>
                <div>{{ $employee->address }}</div>
            </td>
        </tr>
    @endif
</table>

<div style="font-size:11px;font-weight:bold;margin:10px 0 4px 0;">Hours &amp; earnings</div>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse;border-color:#ccc;margin-bottom:12px;">
    <tr>
        <td>Regular hours</td>
        <td align="right" width="25%">{{ number_format((float) $payrollItem->regular_hours, 2) }}</td>
    </tr>
    <tr>
        <td>Vacation hours</td>
        <td align="right">{{ number_format((float) $payrollItem->vacation_hours, 2) }}</td>
    </tr>
    <tr>
        <td>Sick hours</td>
        <td align="right">{{ number_format((float) $payrollItem->sick_hours, 2) }}</td>
    </tr>
    <tr>
        <td>Holiday hours</td>
        <td align="right">{{ number_format((float) $payrollItem->holidays_hours, 2) }}</td>
    </tr>
    <tr>
        <td>Personal hours</td>
        <td align="right">{{ number_format((float) $payrollItem->personal_hours, 2) }}</td>
    </tr>
    <tr>
        <td>Overtime hours</td>
        <td align="right">{{ number_format((float) $payrollItem->overtime_hours, 2) }}</td>
    </tr>
    <tr style="background:#e8f5e9;font-weight:bold;">
        <td>Gross pay</td>
        <td align="right">${{ number_format($gross, 2) }}</td>
    </tr>
</table>

<div style="font-size:11px;font-weight:bold;margin:10px 0 4px 0;">Deductions (employee)</div>
<table width="100%" border="1" cellspacing="0" cellpadding="5" style="border-collapse:collapse;border-color:#ccc;margin-bottom:12px;">
    <tr style="background:#eee;">
        <th align="left">Description</th>
        <th align="right" width="28%">Amount</th>
    </tr>
    <tr><td>401(k)</td><td align="right">${{ number_format($d401, 2) }}</td></tr>
    <tr><td>Federal tax</td><td align="right">${{ number_format($fed, 2) }}</td></tr>
    <tr><td>State tax</td><td align="right">${{ number_format($state, 2) }}</td></tr>
    <tr><td>Local tax</td><td align="right">${{ number_format($local, 2) }}</td></tr>
    <tr><td>Social Security</td><td align="right">${{ number_format($ss, 2) }}</td></tr>
    <tr><td>Medicare</td><td align="right">${{ number_format($medi, 2) }}</td></tr>
    <tr><td>Insurance</td><td align="right">${{ number_format($ins, 2) }}</td></tr>
    <tr><td>Other</td><td align="right">${{ number_format($other, 2) }}</td></tr>
    <tr style="background:#fff8e1;font-weight:bold;">
        <td>Total deductions</td>
        <td align="right">${{ number_format($totalDeductions, 2) }}</td>
    </tr>
</table>

<table width="100%" cellspacing="0" cellpadding="10" style="border:2px solid #1a5c24;margin-bottom:12px;background:#f0fdf4;">
    <tr>
        <td style="font-size:12px;font-weight:bold;text-transform:uppercase;">Net pay</td>
        <td align="right" style="font-size:18px;font-weight:bold;">${{ number_format($netPay, 2) }}</td>
    </tr>
</table>

<div style="font-size:8px;color:#888;text-align:center;border-top:1px dashed #ccc;padding-top:8px;">
    DIY Payroll Solutions
</div>
