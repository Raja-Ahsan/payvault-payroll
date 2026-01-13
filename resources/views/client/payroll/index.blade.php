@extends('layouts.client')

@section('title', 'Payroll Runs')
@section('page-title', 'Payroll Management')
@section('page-description', 'Manage your payroll runs')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800">All Payroll Runs</h3>
        <a href="{{ route('client.payroll.create') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
            <i class="fas fa-plus mr-2"></i>Create Payroll Run
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pay Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pay Date</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Net</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($payrollRuns as $payroll)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $payroll->company->name ?? 'N/A' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}
                        <br><span class="text-xs">{{ ucfirst($payroll->pay_period_type) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $payroll->pay_date->format('M d, Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($payroll->status == 'finalized') bg-green-100 text-green-800
                            @elseif($payroll->status == 'approved') bg-blue-100 text-blue-800
                            @elseif($payroll->status == 'preview') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($payroll->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                        ${{ number_format($payroll->total_net, 2) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('client.payroll.show', $payroll) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                <i class="fas fa-eye"></i>
                            </a>
                            @if($payroll->status == 'draft')
                                <a href="{{ route('client.payroll.edit', $payroll) }}" class="text-green-600 hover:text-green-900" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        No payroll runs found. <a href="{{ route('client.payroll.create') }}" class="text-green-600 hover:text-green-800">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $payrollRuns->links() }}
    </div>
</div>
@endsection
