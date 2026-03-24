@extends('layouts.client')

@section('title', 'Employee Details')
@section('page-title', $employee->first_name . ' ' . $employee->last_name)
@section('page-description', 'Employee information and details')

@section('content')
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center">
                    <div
                        class="w-16 h-16  flex items-center justify-center text-white font-bold text-2xl mr-4">
                        {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                    </div>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-800">{{ $employee->first_name }} {{ $employee->last_name }}
                        </h3>
                        <p class="text-gray-500">{{ $employee->email }}</p>
                    </div>
                </div>
                <a href="{{ route('client.employees.edit', $employee) }}"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-edit mr-2"></i>Edit
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="text-sm font-medium text-gray-500">Company</label>
                    <p class="text-gray-800 mt-1">{{ $employee->company->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Employee Name</label>
                    <p class="text-gray-800 mt-1">{{ $employee->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Employee ID</label>
                    <p class="text-gray-800 mt-1">{{ $employee->employee_id ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Gender</label>
                    <p class="text-gray-800 mt-1">{{ $employee->gender ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Occupation</label>
                    <p class="text-gray-800 mt-1">{{ $employee->occupation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Hire Date</label>
                    <p class="text-gray-800 mt-1">{{ $employee->hire_date ? \Illuminate\Support\Carbon::parse($employee->hire_date)->format('M j, Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Annual Salary</label>
                    <p class="text-gray-800 mt-1">{{ $employee->annual_salary ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Regular Hourly Rate</label>
                    <p class="text-gray-800 mt-1">{{ $employee->regular_hourly_rate ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Overtime Hourly Rate</label>
                    <p class="text-gray-800 mt-1">{{ $employee->overtime_hourly_rate ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">Federal Allowances</label>
                    <p class="text-gray-800 mt-1">{{ $employee->federal_allowances ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-500">401(k) Contribution Percent</label>
                    <p class="text-gray-800 mt-1">
                        @php
                            $k401 = $employee->effective401kContributionPercent();
                        @endphp
                        {{ $k401 > 0 ? rtrim(rtrim(number_format($k401, 2), '0'), '.') . '%' : '—' }}
                    </p>
                </div>


                <div>
                    <label class="text-sm font-medium text-gray-500">Status</label>
                    <p class="mt-1">
                        <span
                            class="px-3 py-1 rounded-full text-sm font-semibold {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </p>
                </div>
            </div>
        </div>

        <!-- Bank Accounts Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Payrolls</h3>
                <a href="{{ route('client.employees.create-payroll', $employee) }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    <i class="fas fa-plus mr-2"></i>Register Payroll
                </a>
            </div>


            <!-- Payrolls List -->
            <div class="space-y-3">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Employee ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($payrollItems as $payrollItem)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $employee->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $employee->employee_id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    {{ $payrollItem->pay_date ? \Illuminate\Support\Carbon::parse($payrollItem->pay_date)->format('M j, Y') : '—' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <a href="{{ route('client.employees.show.payroll', [$employee, $payrollItem]) }}" class="text-blue-600 hover:text-blue-900" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('client.employees.payroll.edit', [$employee, $payrollItem]) }}" class="text-amber-600 hover:text-amber-800" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('client.employees.payroll.destroy', [$employee, $payrollItem]) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Delete this payroll record? This cannot be undone.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 p-0 border-0 bg-transparent cursor-pointer" title="Delete">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    No payroll records yet. Use “Register Payroll” to add one.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($payrollItems->hasPages())
                <div class="mt-4 pt-4 border-t border-gray-200 px-1">
                    {{ $payrollItems->links() }}
                </div>
            @endif
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-semibold text-gray-800">Bank Accounts</h3>
                <button onclick="document.getElementById('addBankAccountForm').classList.toggle('hidden')"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                    <i class="fas fa-plus mr-2"></i>Add Bank Account
                </button>
            </div>

            <!-- Add Bank Account Form -->
            <div id="addBankAccountForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="font-semibold text-gray-800 mb-4">Add New Bank Account</h4>
                <form action="{{ route('client.employees.bank-accounts.store', $employee) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name *</label>
                            <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name *</label>
                            <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}"
                                required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Type *</label>
                            <select name="account_type" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                                <option value="checking" {{ old('account_type') == 'checking' ? 'selected' : '' }}>Checking
                                </option>
                                <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>Savings
                                </option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Routing Number *</label>
                            <input type="text" name="routing_number" value="{{ old('routing_number') }}" required
                                maxlength="9" pattern="[0-9]{9}" placeholder="9 digits"
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Account Number *</label>
                            <input type="text" name="account_number" value="{{ old('account_number') }}" required
                                class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_primary" value="1"
                                    {{ old('is_primary') ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm text-gray-700">Set as Primary Account</span>
                            </label>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center space-x-3">
                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            <i class="fas fa-save mr-2"></i>Add Bank Account
                        </button>
                        <button type="button"
                            onclick="document.getElementById('addBankAccountForm').classList.add('hidden')"
                            class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>

            <!-- Bank Accounts List -->
            <div class="space-y-3">
                @forelse($bankAccounts as $bankAccount)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center space-x-3">
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $bankAccount->bank_name }}</p>
                                    <p class="text-sm text-gray-500">
                                        {{ ucfirst($bankAccount->account_type) }} •
                                        ****{{ substr($bankAccount->account_number, -4) }}
                                    </p>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @if ($bankAccount->is_primary)
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Primary</span>
                                    @endif
                                    @if ($bankAccount->verification_status == 'verified')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                                    @elseif($bankAccount->verification_status == 'pending')
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                    @else
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            @if ($bankAccount->verification_status != 'verified')
                                <form
                                    action="{{ route('client.employees.bank-accounts.verify', [$employee, $bankAccount]) }}"
                                    method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="px-3 py-1 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm">
                                        <i class="fas fa-check mr-1"></i>Verify
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-university text-4xl mb-3 text-gray-300"></i>
                        <p>No bank accounts found. Add a bank account to enable ACH processing.</p>
                    </div>
                @endforelse
            </div>
            @if ($bankAccounts->hasPages())
                <div class="mt-4 pt-4 border-t border-gray-200 px-1">
                    {{ $bankAccounts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
