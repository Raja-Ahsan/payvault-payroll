@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('page-title', 'Dashboard Overview')
@section('page-description', 'Complete overview of your payroll system')

@section('content')
<div class="space-y-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Companies -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Companies</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_companies'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> Active
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-building text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Employees -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Employees</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_employees'] ?? 0 }}</p>
                    <p class="text-xs text-green-600 mt-2">
                        <i class="fas fa-arrow-up"></i> Active
                    </p>
                </div>
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Payroll Runs This Month -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Payroll Runs (Month)</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['payroll_runs_month'] ?? 0 }}</p>
                    <p class="text-xs text-purple-600 mt-2">
                        <i class="fas fa-calendar"></i> This Month
                    </p>
                </div>
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-money-check-alt text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Processed -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Processed</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_processed'] ?? 0, 2) }}</p>
                    <p class="text-xs text-indigo-600 mt-2">
                        <i class="fas fa-dollar-sign"></i> All Time
                    </p>
                </div>
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-indigo-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Payroll Trends -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payroll Trends (Last 6 Months)</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="payrollChart"></canvas>
            </div>
        </div>

        <!-- Status Distribution -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Payroll Status Distribution</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="statusChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payroll Runs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Payroll Runs</h3>
                <a href="{{ route('admin.payroll.index') }}" class="text-sm text-purple-600 hover:text-purple-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_payrolls ?? [] as $payroll)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ $payroll->company->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">{{ $payroll->pay_period_start->format('M d') }} - {{ $payroll->pay_period_end->format('M d, Y') }}</p>
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
                        <p class="text-sm font-semibold text-gray-800 mt-1">${{ number_format($payroll->total_net, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No recent payroll runs</p>
                @endforelse
            </div>
        </div>

        <!-- Recent ACH Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent ACH Transactions</h3>
                <a href="{{ route('admin.ach.index') }}" class="text-sm text-purple-600 hover:text-purple-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_ach ?? [] as $transaction)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ $transaction->transaction_type == 'credit' ? 'Employee Deposit' : 'Company Funding' }}</p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($transaction->status == 'completed') bg-green-100 text-green-800
                            @elseif($transaction->status == 'processing') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->status) }}
                        </span>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${{ number_format($transaction->amount, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No recent ACH transactions</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Payroll Trends Chart
        const payrollCtx = document.getElementById('payrollChart');
        if (payrollCtx) {
            const chartData = {
                labels: @json($chart_data['months'] ?? []),
                amounts: @json($chart_data['amounts'] ?? [])
            };

            new Chart(payrollCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Total Payroll ($)',
                        data: chartData.amounts,
                        borderColor: 'rgb(102, 126, 234)',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(102, 126, 234)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 15
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Total: $' + context.parsed.y.toLocaleString('en-US', {
                                        minimumFractionDigits: 2,
                                        maximumFractionDigits: 2
                                    });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return '$' + value.toLocaleString('en-US', {
                                        minimumFractionDigits: 0,
                                        maximumFractionDigits: 0
                                    });
                                },
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            ticks: {
                                font: {
                                    size: 11
                                }
                            },
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        }

        // Status Distribution Chart
        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
            const statusData = {
                labels: @json($status_data['labels'] ?? []),
                values: @json($status_data['values'] ?? [])
            };

            new Chart(statusCtx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: statusData.labels,
                    datasets: [{
                        data: statusData.values,
                        backgroundColor: [
                            'rgba(34, 197, 94, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(234, 179, 8, 0.8)',
                            'rgba(156, 163, 175, 0.8)'
                        ],
                        borderColor: [
                            'rgba(34, 197, 94, 1)',
                            'rgba(59, 130, 246, 1)',
                            'rgba(234, 179, 8, 1)',
                            'rgba(156, 163, 175, 1)'
                        ],
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true,
                                font: {
                                    size: 12
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed || 0;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return label + ': ' + value + ' (' + percentage + '%)';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection
