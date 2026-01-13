@extends('layouts.client')

@section('title', 'Payroll Details')
@section('page-title', 'Payroll Run Details')
@section('page-description', 'View and manage payroll run')

@section('content')
<div class="space-y-6">
    <!-- Payroll Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $payroll->company->name ?? 'N/A' }}</h3>
                <p class="text-gray-500">{{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }} • {{ ucfirst($payroll->pay_period_type) }}</p>
            </div>
            <div class="flex items-center space-x-2">
                <span class="px-4 py-2 rounded-lg text-sm font-semibold
                    @if($payroll->status == 'finalized') bg-green-100 text-green-800
                    @elseif($payroll->status == 'approved') bg-blue-100 text-blue-800
                    @elseif($payroll->status == 'preview') bg-yellow-100 text-yellow-800
                    @else bg-gray-100 text-gray-800
                    @endif">
                    {{ ucfirst($payroll->status) }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Total Gross</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($payroll->total_gross, 2) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Total Deductions</p>
                <p class="text-2xl font-bold text-gray-800">${{ number_format($payroll->total_deductions, 2) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Total Net</p>
                <p class="text-2xl font-bold text-green-600">${{ number_format($payroll->total_net, 2) }}</p>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-3">
            @if($payroll->status == 'draft')
                <form action="{{ route('client.payroll.calculate', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700">
                        <i class="fas fa-calculator mr-2"></i>Calculate Payroll
                    </button>
                </form>
            @endif

            @if($payroll->status == 'preview')
                <form action="{{ route('client.payroll.approve', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-check mr-2"></i>Approve
                    </button>
                </form>
            @endif

            @if($payroll->status == 'approved')
                <form action="{{ route('client.payroll.finalize', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-lock mr-2"></i>Finalize
                    </button>
                </form>
            @endif

            @if($payroll->status == 'finalized')
                <form action="{{ route('client.payroll.process-ach', $payroll) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-semibold shadow-md">
                        <i class="fas fa-exchange-alt mr-2"></i>Process ACH
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Payroll Items -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Payroll Items ({{ $payroll->payrollItems->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hours</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Gross Pay</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Taxes</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Deductions</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Net Pay</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($payroll->payrollItems as $item)
                    <tr>
                        <td class="px-4 py-3 text-sm font-medium">{{ $item->employee->first_name }} {{ $item->employee->last_name }}</td>
                        <td class="px-4 py-3 text-sm">{{ number_format($item->hours_worked, 2) }}</td>
                        <td class="px-4 py-3 text-sm">${{ number_format($item->gross_pay, 2) }}</td>
                        <td class="px-4 py-3 text-sm">${{ number_format($item->total_taxes, 2) }}</td>
                        <td class="px-4 py-3 text-sm">${{ number_format($item->total_deductions, 2) }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-green-600">${{ number_format($item->net_pay, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No payroll items found. Calculate payroll to generate items.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
