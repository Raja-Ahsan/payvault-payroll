@extends('layouts.employee')

@section('title', 'My Profile')
@section('page-title', 'My Profile')
@section('page-description', 'Manage your profile information')

@section('content')
<div class="space-y-6">
    <!-- Profile Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Personal Information</h3>
        
        <form action="{{ route('employee.profile.update') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
                    <input type="text" value="{{ $employee->first_name }}" disabled
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 text-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Contact administrator to change</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
                    <input type="text" value="{{ $employee->last_name }}" disabled
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 text-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Contact administrator to change</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" value="{{ $employee->email }}" disabled
                        class="w-full px-4 py-2 border rounded-lg bg-gray-50 text-gray-500">
                    <p class="text-xs text-gray-500 mt-1">Contact administrator to change</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone', $employee->phone) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Address</label>
                    <input type="text" name="address" value="{{ old('address', $employee->address) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">City</label>
                    <input type="text" name="city" value="{{ old('city', $employee->city) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">State</label>
                    <input type="text" name="state" value="{{ old('state', $employee->state) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Zip Code</label>
                    <input type="text" name="zip_code" value="{{ old('zip_code', $employee->zip_code) }}"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-save mr-2"></i>Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Account Information -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Account Information</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Company</label>
                <p class="text-gray-800 mt-1">{{ $employee->company->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Employee Number</label>
                <p class="text-gray-800 mt-1">{{ $employee->employee_number ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Pay Type</label>
                <p class="text-gray-800 mt-1">{{ ucfirst($employee->pay_type) }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Rate/Salary</label>
                <p class="text-gray-800 mt-1">
                    @if($employee->pay_type == 'hourly')
                        ${{ number_format($employee->hourly_rate ?? 0, 2) }}/hr
                    @else
                        ${{ number_format($employee->salary ?? 0, 2) }}/year
                    @endif
                </p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Hire Date</label>
                <p class="text-gray-800 mt-1">{{ $employee->hire_date ? $employee->hire_date->format('M d, Y') : 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-6">Change Password</h3>
        
        <form action="{{ route('employee.profile.password') }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Current Password *</label>
                    <input type="password" name="current_password" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">New Password *</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Confirm Password *</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    <i class="fas fa-key mr-2"></i>Update Password
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
