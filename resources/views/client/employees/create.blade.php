@extends('layouts.client')

@section('title', 'Create Employee')
@section('page-title', 'Create New Employee')
@section('page-description', 'Add a new employee to the system')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-4xl">
    <form action="{{ route('client.employees.store') }}" method="POST">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-2">Company *</label>
                <select name="company_id" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="">Select Company</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                <input type="text" name="first_name" value="{{ old('first_name') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                <input type="text" name="last_name" value="{{ old('last_name') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                <input type="text" name="phone" value="{{ old('phone') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pay Type *</label>
                <select name="pay_type" id="pay_type" required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
                    <option value="hourly" {{ old('pay_type') == 'hourly' ? 'selected' : '' }}>Hourly</option>
                    <option value="salary" {{ old('pay_type') == 'salary' ? 'selected' : '' }}>Salary</option>
                </select>
            </div>

            <div id="hourly_rate_field">
                <label class="block text-sm font-medium text-gray-700 mb-2">Hourly Rate *</label>
                <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>

            <div id="salary_field" style="display: none;">
                <label class="block text-sm font-medium text-gray-700 mb-2">Annual Salary *</label>
                <input type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-green-500">
            </div>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-4">
            <a href="{{ route('client.employees.index') }}" class="px-6 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
                <i class="fas fa-save mr-2"></i>Create Employee
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('pay_type').addEventListener('change', function() {
        const payType = this.value;
        const hourlyField = document.getElementById('hourly_rate_field');
        const salaryField = document.getElementById('salary_field');
        
        if (payType === 'hourly') {
            hourlyField.style.display = 'block';
            salaryField.style.display = 'none';
            hourlyField.querySelector('input').required = true;
            salaryField.querySelector('input').required = false;
        } else {
            hourlyField.style.display = 'none';
            salaryField.style.display = 'block';
            hourlyField.querySelector('input').required = false;
            salaryField.querySelector('input').required = true;
        }
    });
    
    // Trigger on page load
    document.getElementById('pay_type').dispatchEvent(new Event('change'));
</script>
@endpush
@endsection
