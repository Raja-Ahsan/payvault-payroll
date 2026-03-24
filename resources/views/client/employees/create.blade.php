@extends('layouts.client')

@section('title', 'Create Employee')
@section('page-title', 'Create New Employee')
@section('page-description', 'Add a new employee to the system')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('client.employees.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company *</label>
                    <select name="company_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="">Select Company</option>
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="male">Male</option>
                        <option value="female">Female</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                    <input type="date" name="hire_date" value="{{ old('hire_date') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Annual Salary</label>
                    <input type="number" step="0.01" name="annual_salary" value="{{ old('annual_salary') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Hourly Rate</label>
                    <input type="number" step="0.01" name="regular_hourly_rate"
                        value="{{ old('regular_hourly_rate') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hourly Rate</label>
                    <input type="number" step="0.01" name="overtime_hourly_rate"
                        value="{{ old('overtime_hourly_rate') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Federal Allowances</label>
                    <input type="number" step="0.01" name="federal_allowances" value="{{ old('federal_allowances') }}"
                        required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">401(k) contribution (% of gross)</label>
                    <input type="number" step="0.01" min="0" max="100" name="401_k_contrib_percent"
                        value="{{ old('401_k_contrib_percent') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                        placeholder="e.g. 3 for 3%">
                    <p class="text-xs text-gray-500 mt-1">Enter <strong>3</strong> for 3% — not 300. Each pay, deduction = gross pay × (this % ÷ 100).</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Deduction</label>
                    <input type="number" step="0.01" name="insurance_deduction"
                        value="{{ old('insurance_deduction') }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Other Deductions</label>
                    <input type="number" step="0.01" name="other_deductions" value="{{ old('other_deductions') }}"
                        required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('client.employees.index') }}"
                    class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 btn-gradient text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
                    <i class="fas fa-save mr-2"></i>Create Employee
                </button>
            </div>
        </form>
    </div>
@endsection
