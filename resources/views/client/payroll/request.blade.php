@extends('layouts.client')

@section('title', 'Request Payroll')
@section('page-title', 'Request Payroll')
@section('page-description', 'Filter payroll records by pay date range')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="get" action="{{ route('client.payroll.request') }}" class="flex flex-col gap-4 sm:flex-row sm:flex-wrap sm:items-end">
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
                <div class="flex gap-2">
                    <button type="submit"
                        class="inline-flex items-center justify-center px-5 py-2 rounded-lg text-sm font-medium text-white btn-gradient hover:opacity-95">
                        <i class="fas fa-search mr-2"></i>
                        Show records
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
                        Pay dates from <strong>{{ \Illuminate\Support\Carbon::parse(request('from_date'))->format('M j, Y') }}</strong>
                        to <strong>{{ \Illuminate\Support\Carbon::parse(request('to_date'))->format('M j, Y') }}</strong>
                    </p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Employee ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay period</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Gross</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Net pay</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
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
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $item->pay_period }}">
                                        {{ $item->pay_period ?? '—' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->gross_pay, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right font-mono">
                                        ${{ number_format((float) $item->net_pay, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <a href="{{ route('client.employees.show.payroll', [$emp, $item]) }}"
                                            class="text-blue-600 hover:text-blue-900" title="View paystub">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-sm text-gray-500">
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
                Choose a <strong>from</strong> and <strong>to</strong> date, then click <strong>Show records</strong> to list payroll by pay date.
            </div>
        @endif
    </div>
@endsection
