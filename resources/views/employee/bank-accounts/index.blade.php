@extends('layouts.employee')

@section('title', 'Bank Accounts')
@section('page-title', 'My Bank Accounts')
@section('page-description', 'Manage your bank accounts for direct deposit')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-semibold text-gray-800">Bank Accounts</h3>
            <button onclick="document.getElementById('addBankAccountForm').classList.toggle('hidden')" 
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                <i class="fas fa-plus mr-2"></i>Add Bank Account
            </button>
        </div>

        <!-- Add Bank Account Form -->
        <div id="addBankAccountForm" class="hidden mb-6 p-4 bg-gray-50 rounded-lg">
            <h4 class="font-semibold text-gray-800 mb-4">Add New Bank Account</h4>
            <form action="{{ route('employee.bank-accounts.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bank Name *</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name') }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Holder Name *</label>
                        <input type="text" name="account_holder_name" value="{{ old('account_holder_name') }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Type *</label>
                        <select name="account_type" required class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            <option value="checking" {{ old('account_type') == 'checking' ? 'selected' : '' }}>Checking</option>
                            <option value="savings" {{ old('account_type') == 'savings' ? 'selected' : '' }}>Savings</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Routing Number *</label>
                        <input type="text" name="routing_number" value="{{ old('routing_number') }}" required maxlength="9"
                            pattern="[0-9]{9}" placeholder="9 digits"
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Account Number *</label>
                        <input type="text" name="account_number" value="{{ old('account_number') }}" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}
                                class="mr-2">
                            <span class="text-sm text-gray-700">Set as Primary Account</span>
                        </label>
                    </div>
                </div>
                <div class="mt-4 flex items-center space-x-3">
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-save mr-2"></i>Add Bank Account
                    </button>
                    <button type="button" onclick="document.getElementById('addBankAccountForm').classList.add('hidden')" 
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
                            @if($bankAccount->is_primary)
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Primary</span>
                            @endif
                            @if($bankAccount->verification_status == 'verified')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Verified</span>
                            @elseif($bankAccount->verification_status == 'pending')
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @else
                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Failed</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    @if($bankAccount->verification_status != 'verified')
                        <form action="{{ route('employee.bank-accounts.verify', $bankAccount) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm">
                                <i class="fas fa-check mr-1"></i>Verify
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-8 text-gray-500">
                <i class="fas fa-university text-4xl mb-3 text-gray-300"></i>
                <p>No bank accounts found. Add a bank account to receive direct deposits.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
