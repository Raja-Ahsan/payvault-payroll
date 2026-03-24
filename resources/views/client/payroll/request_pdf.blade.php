<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Payroll records</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6px;
            color: #111;
        }

        h1 {
            font-size: 12px;
            margin: 0 0 6px 0;
        }

        .meta {
            font-size: 7px;
            color: #444;
            margin: 0 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 2px 3px;
            vertical-align: top;
            word-wrap: break-word;
        }

        th {
            background: #eee;
            font-weight: bold;
            text-align: left;
            font-size: 5.5px;
        }

        .right {
            text-align: right;
        }

        .muted {
            color: #555;
        }
    </style>
</head>

<body>
    <h1>Payroll records</h1>
    <p class="meta">
        Pay dates from {{ \Illuminate\Support\Carbon::parse($fromDate)->format('M j, Y') }}
        to {{ \Illuminate\Support\Carbon::parse($toDate)->format('M j, Y') }}
        &nbsp;|&nbsp; Generated {{ now()->format('M j, Y g:i A') }}
    </p>
    <table>
        <thead>
            <tr>
                <th>Employee</th>
                <th>Employee ID</th>
                <th>Company</th>
                <th>Pay date</th>
                <th>Pay period</th>
                <th>REGULAR PAY RATE</th>
                <th>REGULAR HOURS</th>
                <th>OverTime Rate</th>
                <th>OverTime Hours</th>
                <th class="right">Gross Pay</th>
                <th class="right">401(k)</th>
                <th class="right">Federal Tax</th>
                <th class="right">State Tax</th>
                <th class="right">local Tax</th>
                <th class="right">social security</th>
                <th class="right">medicare</th>
                <th class="right">insurance</th>
                <th class="right">other</th>
                <th class="right">Net pay</th>
                <th>Bank Name</th>
                <th>Account Holder Name</th>
                <th>Account Type</th>
                <th>Routing Number</th>
                <th>Account Number</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payrollItems as $item)
                @php
                    $emp = $item->employee;
                    $ba = $emp->primaryBankAccountForPayroll();
                @endphp
                <tr>
                    <td>{{ $emp->name ?? '—' }}</td>
                    <td>{{ $emp->employee_id ?? '—' }}</td>
                    <td>{{ $emp->company->name ?? '—' }}</td>
                    <td>{{ $item->pay_date ? \Illuminate\Support\Carbon::parse($item->pay_date)->format('M j, Y') : '—' }}
                    </td>
                    <td class="muted">{{ $item->pay_period ?? '—' }}</td>
                    <td>${{ number_format((float) $emp->regular_hourly_rate, 2) }}</td>
                    <td>{{ number_format((float) $item->regular_hours) }}</td>
                    <td>${{ number_format((float) $emp->overtime_hourly_rate) }}</td>
                    <td>{{ number_format((float) $item->overtime_hours) }}</td>
                    <td class="right">${{ number_format((float) $item->gross_pay, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->k401_amount, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->fed_tax, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->state_tax, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->local_tax, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->social_security, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->medi_care, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->insurance_deduction, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->other_deductions, 2) }}</td>
                    <td class="right">${{ number_format((float) $item->net_pay, 2) }}</td>
                    @if ($ba)
                        <td>
                            {{ $ba->bank_name }}
                        </td>
                        <td>
                            {{ $ba->account_holder_name }}
                        </td>
                        <td>
                            {{ $ba->account_type }}
                        </td>
                        <td>
                            {{ $ba->routing_number }}
                        </td>
                        <td>
                            {{ $ba->account_number }}
                        @else
                            <td>—</td>
                            <td>—</td>
                            <td>—</td>
                            <td>—</td>
                            <td>—</td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="20" style="text-align:center;padding:12px;">No payroll records in this date range.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>
