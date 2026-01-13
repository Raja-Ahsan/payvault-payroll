@extends('layouts.employee')

@section('title', 'Employee Dashboard')
@section('page-title', 'My Dashboard')
@section('page-description', 'Welcome back, ' . $employee->first_name)

@section('content')
<div class="space-y-6">
    <!-- Welcome Card -->
    <div class="bg-gradient-to-r from-blue-600 to-cyan-600 rounded-lg shadow-md p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold mb-2">Welcome, {{ $employee->first_name }}!</h2>
                <p class="text-blue-100">{{ $employee->company->name ?? 'N/A' }} • Employee ID: {{ $employee->employee_number ?? 'N/A' }}</p>
            </div>
            <div class="w-20 h-20 rounded-full bg-white bg-opacity-20 flex items-center justify-center text-4xl font-bold">
                {{ strtoupper(substr($employee->first_name, 0, 1)) }}
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Payrolls -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Payrolls</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['total_payrolls'] ?? 0 }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-calendar-check"></i> All Time
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice-dollar text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Earnings</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['total_earnings'] ?? 0, 2) }}</p>
                    <p class="text-xs text-cyan-600 mt-2">
                        <i class="fas fa-dollar-sign"></i> All Time
                    </p>
                </div>
                <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-chart-line text-cyan-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- This Month Earnings -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">This Month</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($stats['this_month_earnings'] ?? 0, 2) }}</p>
                    <p class="text-xs text-blue-600 mt-2">
                        <i class="fas fa-calendar"></i> {{ now()->format('M Y') }}
                    </p>
                </div>
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-wallet text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Bank Accounts -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-effect">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Bank Accounts</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $stats['bank_accounts'] ?? 0 }}</p>
                    <p class="text-xs text-cyan-600 mt-2">
                        <i class="fas fa-university"></i> Active
                    </p>
                </div>
                <div class="w-16 h-16 bg-cyan-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-university text-cyan-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Earnings Trends -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Earnings Trends (Last 6 Months)</h3>
            <div style="position: relative; height: 300px;">
                <canvas id="earningsChart"></canvas>
            </div>
        </div>

        <!-- Pay Type Info -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Pay Information</h3>
            <div class="space-y-4">
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Pay Type</p>
                    <p class="text-2xl font-bold text-gray-800 mt-1">{{ ucfirst($employee->pay_type) }}</p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">
                        @if($employee->pay_type == 'hourly')
                            Hourly Rate
                        @else
                            Annual Salary
                        @endif
                    </p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">
                        @if($employee->pay_type == 'hourly')
                            ${{ number_format($employee->hourly_rate ?? 0, 2) }}/hr
                        @else
                            ${{ number_format($employee->salary ?? 0, 2) }}
                        @endif
                    </p>
                </div>
                <div class="p-4 bg-gray-50 rounded-lg">
                    <p class="text-sm text-gray-500">Company</p>
                    <p class="text-lg font-semibold text-gray-800 mt-1">{{ $employee->company->name ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Payrolls -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Payrolls</h3>
                <a href="{{ route('employee.payroll.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_payrolls ?? [] as $payrollItem)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">{{ $payrollItem->payrollRun->company->name ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-500">
                            @if($payrollItem->payrollRun && $payrollItem->payrollRun->pay_period_start && $payrollItem->payrollRun->pay_period_end)
                                {{ $payrollItem->payrollRun->pay_period_start->format('M d') }} - {{ $payrollItem->payrollRun->pay_period_end->format('M d, Y') }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-gray-800">${{ number_format($payrollItem->net_pay ?? 0, 2) }}</p>
                        <p class="text-xs text-gray-500">{{ number_format($payrollItem->hours_worked ?? 0, 2) }} hrs</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No payroll records found</p>
                @endforelse
            </div>
        </div>

        <!-- Recent ACH Transactions -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-800">Recent Deposits</h3>
                <a href="{{ route('employee.bank-accounts.index') }}" class="text-sm text-blue-600 hover:text-blue-800">View All</a>
            </div>
            <div class="space-y-4">
                @forelse($recent_ach ?? [] as $transaction)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div>
                        <p class="font-medium text-gray-800">Direct Deposit</p>
                        <p class="text-sm text-gray-500">{{ $transaction->created_at ? $transaction->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                    </div>
                    <div class="text-right">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($transaction->status == 'completed') bg-green-100 text-green-800
                            @elseif($transaction->status == 'processing') bg-yellow-100 text-yellow-800
                            @elseif($transaction->status == 'failed') bg-red-100 text-red-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ ucfirst($transaction->status ?? 'pending') }}
                        </span>
                        <p class="text-sm font-semibold text-gray-800 mt-1">${{ number_format($transaction->amount ?? 0, 2) }}</p>
                    </div>
                </div>
                @empty
                <p class="text-gray-500 text-center py-8">No recent deposits</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Earnings Trends Chart
        const earningsCtx = document.getElementById('earningsChart');
        if (earningsCtx) {
            const chartData = {
                labels: @json($chart_data['months'] ?? []),
                amounts: @json($chart_data['amounts'] ?? [])
            };

            new Chart(earningsCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [{
                        label: 'Earnings ($)',
                        data: chartData.amounts,
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: 'rgb(59, 130, 246)',
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
                                    return 'Earnings: $' + context.parsed.y.toLocaleString('en-US', {
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
    });
</script>
@endpush
@endsection
