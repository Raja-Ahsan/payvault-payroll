@extends('layouts.client')

@section('title', 'Company Details')
@section('page-title', $company->name)
@section('page-description', 'Company information and details')

@section('content')
<div class="space-y-6">
    <!-- Company Info Card -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold text-2xl mr-4">
                    {{ strtoupper(substr($company->name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $company->name }}</h3>
                    <p class="text-gray-500">{{ $company->legal_name }}</p>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="{{ route('client.companies.edit', $company) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">EIN</label>
                <p class="text-gray-800">{{ $company->ein ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Email</label>
                <p class="text-gray-800">{{ $company->email ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="text-gray-800">{{ $company->phone ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">ACH Status</label>
                <p class="text-gray-800">
                    @if($company->ach_enrolled)
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            {{ ucfirst($company->ach_status ?? 'Active') }}
                        </span>
                    @else
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                            Not Enrolled
                        </span>
                    @endif
                </p>
            </div>
            <div class="md:col-span-2">
                <label class="text-sm font-medium text-gray-500">Address</label>
                <p class="text-gray-800">
                    {{ $company->address }}<br>
                    {{ $company->city }}, {{ $company->state }} {{ $company->zip_code }}
                </p>
            </div>
        </div>
    </div>

    <!-- Employees Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Employees ({{ $company->employees->count() }})</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pay Type</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($company->employees as $employee)
                    <tr>
                        <td class="px-4 py-3 text-sm">{{ $employee->first_name }} {{ $employee->last_name }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500">{{ $employee->email }}</td>
                        <td class="px-4 py-3 text-sm">{{ ucfirst($employee->pay_type) }}</td>
                        <td class="px-4 py-3">
                            <span class="px-2 py-1 text-xs rounded-full {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $employee->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">No employees found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Payroll Runs Section -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Payroll Runs ({{ $company->payrollRuns->count() }})</h3>
        <div class="space-y-3">
            @forelse($company->payrollRuns->take(5) as $payroll)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium">{{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}</p>
                    <p class="text-sm text-gray-500">{{ ucfirst($payroll->pay_period_type) }} • Pay Date: {{ $payroll->pay_date->format('M d, Y') }}</p>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($payroll->status == 'finalized') bg-green-100 text-green-800
                        @elseif($payroll->status == 'approved') bg-blue-100 text-blue-800
                        @elseif($payroll->status == 'preview') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ ucfirst($payroll->status) }}
                    </span>
                    <p class="text-sm font-semibold mt-1">${{ number_format($payroll->total_net, 2) }}</p>
                </div>
            </div>
            @empty
            <p class="text-gray-500 text-center py-8">No payroll runs found</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
