@extends('layouts.client')

@section('title', 'Edit Payroll Run')
@section('page-title', 'Edit Payroll Run')
@section('page-description', 'Update payroll run information')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl">
    <form action="{{ route('client.payroll.update', $payroll) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Company</label>
                <select name="company_id" class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500" disabled>
                    <option>{{ $payroll->company->name ?? 'N/A' }}</option>
                </select>
                <input type="hidden" name="company_id" value="{{ $payroll->company_id }}">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Type *</label>
                <select name="pay_period_type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="weekly" {{ old('pay_period_type', $payroll->pay_period_type) == 'weekly' ? 'selected' : '' }}>Weekly</option>
                    <option value="biweekly" {{ old('pay_period_type', $payroll->pay_period_type) == 'biweekly' ? 'selected' : '' }}>Biweekly</option>
                    <option value="semimonthly" {{ old('pay_period_type', $payroll->pay_period_type) == 'semimonthly' ? 'selected' : '' }}>Semi-Monthly</option>
                    <option value="monthly" {{ old('pay_period_type', $payroll->pay_period_type) == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period Start *</label>
                <input type="date" name="pay_period_start" value="{{ old('pay_period_start', $payroll->pay_period_start->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Period End *</label>
                <input type="date" name="pay_period_end" value="{{ old('pay_period_end', $payroll->pay_period_end->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Date *</label>
                <input type="date" name="pay_date" value="{{ old('pay_date', $payroll->pay_date->format('Y-m-d')) }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-4">
            <a href="{{ route('client.payroll.show', $payroll) }}" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
                <i class="fas fa-save mr-2"></i>Update Payroll Run
            </button>
        </div>
    </form>
</div>
@endsection
