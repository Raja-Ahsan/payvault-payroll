@extends('layouts.employee')

@section('title', 'Payroll Details')
@section('page-title', 'Payroll Details')
@section('page-description', 'View detailed payroll information')

@section('content')
<div class="space-y-6">
    <!-- Payroll Summary Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $payrollItem->payrollRun->company->name ?? 'N/A' }}</h3>
                <p class="text-gray-500">
                    {{ $payrollItem->payrollRun->pay_period_start->format('M d') }} - {{ $payrollItem->payrollRun->pay_period_end->format('M d, Y') }} • 
                    {{ ucfirst($payrollItem->payrollRun->pay_period_type) }}
                </p>
            </div>
            <div>
                <span class="px-4 py-2 rounded-lg text-sm font-semibold bg-green-100 text-green-800">
                    Finalized
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Hours Worked</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">{{ number_format($payrollItem->hours_worked ?? 0, 2) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Gross Pay</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($payrollItem->gross_pay, 2) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Total Deductions</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">${{ number_format($payrollItem->total_deductions, 2) }}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-500">Net Pay</p>
                <p class="text-2xl font-bold text-blue-600 mt-1">${{ number_format($payrollItem->net_pay, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Tax Breakdown -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Tax Breakdown</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Federal Tax</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">${{ number_format($payrollItem->federal_tax ?? 0, 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">State Tax</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">${{ number_format($payrollItem->state_tax ?? 0, 2) }}</p>
            </div>
            <div class="p-4 bg-gray-50 rounded-lg">
                <p class="text-sm text-gray-500">Local Tax</p>
                <p class="text-lg font-semibold text-gray-800 mt-1">${{ number_format($payrollItem->local_tax ?? 0, 2) }}</p>
            </div>
        </div>
    </div>

    <!-- Deductions -->
    @if($payrollItem->deductions->count() > 0)
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Deductions</h3>
        <div class="space-y-3">
            @foreach($payrollItem->deductions as $deduction)
            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-800">{{ $deduction->description ?? 'Deduction' }}</p>
                    <p class="text-sm text-gray-500">{{ ucfirst($deduction->deduction_type ?? 'N/A') }}</p>
                </div>
                <p class="text-lg font-semibold text-gray-800">${{ number_format($deduction->amount, 2) }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
