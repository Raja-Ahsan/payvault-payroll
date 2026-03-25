@extends('layouts.client')

@section('title', 'Edit Employee')
@section('page-title', 'Edit Employee')
@section('page-description', 'Update employee information')

@section('content')
    <div class="bg-white rounded-lg shadow-md p-6">
        <form action="{{ route('client.employees.update', $employee) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Company *</label>
                    <select name="company_id" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        @foreach ($companies as $company)
                            <option value="{{ $company->id }}"
                                {{ old('company_id', $employee->company_id) == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Name</label>
                    <input type="text" name="name" value="{{ old('name', $employee->name) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Employee ID</label>
                    <input type="text" name="employee_id" value="{{ old('employee_id', $employee->employee_id) }}"
                        required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $employee->address) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select name="gender" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                        <option value="male" {{ old('gender', $employee->gender) == 'male' ? 'selected' : '' }}>Male
                        </option>
                        <option value="female" {{ old('gender', $employee->gender) == 'female' ? 'selected' : '' }}>Female
                        </option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Occupation</label>
                    <input type="text" name="occupation" value="{{ old('occupation', $employee->occupation) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Hire Date</label>
                    <input type="date" name="hire_date"
                        value="{{ old('hire_date', $employee->hire_date?->format('Y-m-d')) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Annual Salary ($)</label>
                    <input type="number" step="0.01" name="annual_salary"
                        value="{{ old('annual_salary', $employee->annual_salary) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Regular Hourly Rate ($)</label>
                    <input type="number" step="0.01" name="regular_hourly_rate"
                        value="{{ old('regular_hourly_rate', $employee->regular_hourly_rate) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Overtime Hourly Rate ($)</label>
                    <input type="number" step="0.01" name="overtime_hourly_rate"
                        value="{{ old('overtime_hourly_rate', $employee->overtime_hourly_rate) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Federal Allowances (W-4)</label>
                    <input type="number" step="0.01" name="federal_allowances"
                        value="{{ old('federal_allowances', $employee->federal_allowances) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">401(k) contrib.</label>
                    <input type="number" step="0.01" min="0" max="100" name="401_k_contrib_percent"
                        value="{{ old('401_k_contrib_percent', $employee->effective401kContributionPercent()) }}"
                        required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500"
                        placeholder="e.g. 3 for 3%">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Insurance Deduction ($)</label>
                    <input type="number" step="0.01" name="insurance_deduction"
                        value="{{ old('insurance_deduction', $employee->insurance_deduction) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Other Deductions ($)</label>
                    <input type="number" step="0.01" name="other_deductions"
                        value="{{ old('other_deductions', $employee->other_deductions) }}" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end space-x-4">
                <a href="{{ route('client.employees.index') }}"
                    class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                    Cancel
                </a>
                <button type="submit"
                    class="px-6 py-2 btn-gradient text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
                    <i class="fas fa-save mr-2"></i>Update Employee
                </button>
            </div>
        </form>
    </div>
@endsection
