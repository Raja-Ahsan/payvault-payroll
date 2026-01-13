@extends('layouts.admin')

@section('title', 'Employee Details')
@section('page-title', $employee->first_name . ' ' . $employee->last_name)
@section('page-description', 'Employee information and details')

@section('content')
<div class="space-y-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="w-16 h-16 rounded-full bg-gradient-to-r from-green-600 to-teal-600 flex items-center justify-center text-white font-bold text-2xl mr-4">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $employee->first_name }} {{ $employee->last_name }}</h3>
                    <p class="text-gray-500">{{ $employee->email }}</p>
                </div>
            </div>
            <a href="{{ route('admin.employees.edit', $employee) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                <i class="fas fa-edit mr-2"></i>Edit
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="text-sm font-medium text-gray-500">Company</label>
                <p class="text-gray-800 mt-1">{{ $employee->company->name ?? 'N/A' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-500">Phone</label>
                <p class="text-gray-800 mt-1">{{ $employee->phone ?? 'N/A' }}</p>
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
                <label class="text-sm font-medium text-gray-500">Status</label>
                <p class="mt-1">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $employee->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
