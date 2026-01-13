@extends('layouts.client')

@section('title', 'Employees')
@section('page-title', 'Employee Management')
@section('page-description', 'Manage your employees')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800">All Employees</h3>
        <a href="{{ route('client.employees.create') }}" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition">
            <i class="fas fa-plus mr-2"></i>Add New Employee
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Company</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pay Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rate/Salary</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($employees as $employee)
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-r from-green-600 to-emerald-600 flex items-center justify-center text-white font-bold mr-3">
                                {{ strtoupper(substr($employee->first_name, 0, 1)) }}
                            </div>
                            <div class="text-sm font-medium text-gray-900">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $employee->company->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ ucfirst($employee->pay_type) }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        @if($employee->pay_type == 'hourly')
                            ${{ number_format($employee->hourly_rate ?? 0, 2) }}/hr
                        @else
                            ${{ number_format($employee->salary ?? 0, 2) }}
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $employee->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $employee->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('client.employees.show', $employee) }}" class="text-blue-600 hover:text-blue-900">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('client.employees.edit', $employee) }}" class="text-green-600 hover:text-green-900">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('client.employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirmDelete()">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No employees found. <a href="{{ route('client.employees.create') }}" class="text-green-600 hover:text-green-800">Create one now</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
@endsection
