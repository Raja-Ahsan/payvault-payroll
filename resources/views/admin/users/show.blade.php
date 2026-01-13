@extends('layouts.admin')

@section('title', 'User Details')
@section('page-title', $user->name)
@section('page-description', 'User information and details')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6 max-w-2xl">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <div class="w-16 h-16 rounded-full bg-gradient-to-r from-purple-600 to-indigo-600 flex items-center justify-center text-white font-bold text-2xl mr-4">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <h3 class="text-2xl font-bold text-gray-800">{{ $user->name }}</h3>
                <p class="text-gray-500">{{ $user->email }}</p>
            </div>
        </div>
        <a href="{{ route('admin.users.edit', $user) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
            <i class="fas fa-edit mr-2"></i>Edit
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="text-sm font-medium text-gray-500">Role</label>
            <p class="text-gray-800 mt-1">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    @if($user->role->name == 'admin') bg-purple-100 text-purple-800
                    @elseif($user->role->name == 'client') bg-blue-100 text-blue-800
                    @else bg-green-100 text-green-800
                    @endif">
                    {{ ucfirst($user->role->name ?? 'No Role') }}
                </span>
            </p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Account Created</label>
            <p class="text-gray-800 mt-1">{{ $user->created_at->format('M d, Y') }}</p>
        </div>
        <div>
            <label class="text-sm font-medium text-gray-500">Last Updated</label>
            <p class="text-gray-800 mt-1">{{ $user->updated_at->format('M d, Y H:i') }}</p>
        </div>
    </div>
</div>
@endsection
