<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Employee Dashboard') - PayVault Payroll</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <style>
        .sidebar-transition {
            transition: all 0.3s ease;
        }
        .active-menu {
            background: linear-gradient(135deg, #3b82f6 0%, #06b6d4 100%);
            color: white;
        }
        .hover-effect:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="w-64 bg-white shadow-lg sidebar-transition">
            <div class="flex flex-col h-full">
                <!-- Logo -->
                <div class="p-6 border-b">
                    <h1 class="text-2xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 bg-clip-text text-transparent">
                        <i class="fas fa-vault mr-2"></i>PayVault
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">Employee Portal</p>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 overflow-y-auto p-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.dashboard') ? 'active-menu' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-home w-5 mr-3"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('employee.payroll.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.payroll.*') ? 'active-menu' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-money-check-alt w-5 mr-3"></i>
                                <span>My Payroll</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('employee.bank-accounts.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.bank-accounts.*') ? 'active-menu' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-university w-5 mr-3"></i>
                                <span>Bank Accounts</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('employee.profile') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('employee.profile') ? 'active-menu' : 'text-gray-700 hover:bg-gray-100' }}">
                                <i class="fas fa-user w-5 mr-3"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- User Info -->
                <div class="p-4 border-t">
                    <div class="flex items-center mb-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-r from-blue-600 to-cyan-600 flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ ucfirst(Auth::user()->role->name ?? 'Employee') }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('web.logout') }}">
                        @csrf
                        <button type="submit" class="w-full flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm border-b">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-sm text-gray-500">@yield('page-description', 'Welcome back, ' . Auth::user()->name)</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search..." class="pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                        </div>
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                            <i class="fas fa-bell text-gray-600"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border-l-4 border-green-500 text-green-700 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p>{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <p>{{ session('error') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('warning'))
                    <div class="mb-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <p>{{ session('warning') }}</p>
                        </div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-4 bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded">
                        <div class="flex">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Simple AJAX helper
        function makeRequest(url, method = 'GET', data = null) {
            return fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: data ? JSON.stringify(data) : null
            }).then(response => response.json());
        }

        // Confirm delete
        function confirmDelete(message = 'Are you sure you want to delete this?') {
            return confirm(message);
        }
    </script>
    @stack('scripts')
</body>
</html>
