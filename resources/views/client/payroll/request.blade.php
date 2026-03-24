@extends('layouts.client')

@section('title', 'Request Payroll')
@section('page-title', 'Request Payroll')
@section('page-description', 'Filter payroll records by pay date range')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="get" action="{{ route('client.payroll.request') }}"
                class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
                <div class="flex-1 min-w-[160px]">
                    <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1">From date</label>
                    <input type="date" name="from_date" id="from_date" value="{{ old('from_date', request('from_date')) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    @error('from_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex-1 min-w-[160px]">
                    <label for="to_date" class="block text-sm font-medium text-gray-700 mb-1">To date</label>
                    <input type="date" name="to_date" id="to_date" value="{{ old('to_date', request('to_date')) }}"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:ring-2 focus:ring-green-600 focus:border-green-600">
                    @error('to_date')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex flex-wrap gap-2">
                    <button type="submit" formaction="{{ route('client.payroll.request') }}"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-medium text-white btn-gradient hover:opacity-95">
                        <i class="fas fa-search mr-2"></i>
                        Show records
                    </button>
                    <button type="submit" formaction="{{ route('client.payroll.request.pdf') }}"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-800 bg-gray-50 hover:bg-gray-100">
                        <i class="fas fa-file-pdf mr-2 text-red-600"></i>
                        Download records
                    </button>
                    @if ($filtersApplied)
                        <a href="{{ route('client.payroll.request') }}"
                            class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-medium border border-gray-300 text-gray-700 hover:bg-gray-50">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if ($filtersApplied)
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">Payroll records</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Pay dates from
                        <strong>{{ \Illuminate\Support\Carbon::parse(request('from_date'))->format('M j, Y') }}</strong>
                        to <strong>{{ \Illuminate\Support\Carbon::parse(request('to_date'))->format('M j, Y') }}</strong>
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Employee ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pay date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pay period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    REGULAR PAY RATE</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    REGULAR HOURS</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    OverTime Rate</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    OverTime Hours</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gross Pay</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    401(k)</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Federal Tax</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    State Tax</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    local Tax</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    social security</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    medicare</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    insurance</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    other</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Net pay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Bank Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account Holder Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Routing Number</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Account Number</th>

                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($payrollItems as $item)
                                @php
                                    $emp = $item->employee;
                                @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $emp->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $emp->employee_id ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $emp->company->name ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $item->pay_date ? \Illuminate\Support\Carbon::parse($item->pay_date)->format('M j, Y') : '—' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate"
                                        title="{{ $item->pay_period }}">
                                        {{ $item->pay_period ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                        ${{ number_format((float) $emp->regular_hourly_rate, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                        {{ number_format((float) $item->regular_hours) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                        ${{ number_format((float) $emp->overtime_hourly_rate) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-mono">
                                        {{ number_format((float) $item->overtime_hours) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->gross_pay, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->k401_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->fed_tax, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->state_tax, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->local_tax, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->social_security, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->medi_care, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->insurance_deduction, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->other_deductions, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->net_pay, 2) }}
                                    </td>
                                    @php
                                        $ba = $emp->primaryBankAccountForPayroll();
                                    @endphp
                                    @if ($ba)
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $ba->bank_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $ba->account_holder_name }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $ba->account_type }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $ba->routing_number }}
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                            {{ $ba->account_number }}
                                        </td>
                                    @else
                                        <td class="text-gray-400 text-center">—</td>
                                        <td class="text-gray-400 text-center">—</td>
                                        <td class="text-gray-400 text-center">—</td>
                                        <td class="text-gray-400 text-center">—</td>
                                        <td class="text-gray-400 text-center">—</td>
                                    @endif
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('client.employees.show.payroll', [$emp, $item]) }}"
                                            class="text-blue-600 hover:text-blue-900" title="View paystub">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="25" class="px-6 py-12 text-center text-sm text-gray-500">
                                        No payroll records in this date range.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if ($payrollItems->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $payrollItems->links() }}
                    </div>
                @endif
            </div>
        @else
            <div class="bg-white rounded-lg shadow-md p-8 text-center text-gray-500 text-sm">
                Choose a <strong>from</strong> and <strong>to</strong> date, then click <strong>Show records</strong> to
                list payroll by pay date.
            </div>
        @endif
    </div>
@endsection
