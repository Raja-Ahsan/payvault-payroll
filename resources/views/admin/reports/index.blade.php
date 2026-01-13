@extends('layouts.admin')

@section('title', 'Reports')
@section('page-title', 'Reports & Analytics')
@section('page-description', 'View comprehensive payroll reports')

@section('content')
<div class="space-y-6">
    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payroll by Company</h3>
            <div class="space-y-3">
                @forelse($companyPayrolls as $cp)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ $cp->company->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $cp->count }} payroll runs</p>
                    </div>
                    <p class="text-lg font-bold text-purple-600">${{ number_format($cp->total, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Monthly Trends</h3>
            <div class="space-y-3">
                @forelse($monthlyData as $month)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ date('M Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</p>
                        <p class="text-sm text-gray-500">{{ $month->count }} runs</p>
                    </div>
                    <p class="text-lg font-bold text-indigo-600">${{ number_format($month->total, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500 text-center py-4">No data available</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Breakdown</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Finalized</span>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full font-semibold">
                        {{ $statusBreakdown['finalized'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Approved</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full font-semibold">
                        {{ $statusBreakdown['approved'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Preview</span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full font-semibold">
                        {{ $statusBreakdown['preview'] ?? 0 }}
                    </span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <span class="text-gray-700">Draft</span>
                    <span class="px-3 py-1 bg-gray-100 text-gray-800 rounded-full font-semibold">
                        {{ $statusBreakdown['draft'] ?? 0 }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
