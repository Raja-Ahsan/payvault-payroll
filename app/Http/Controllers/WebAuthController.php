<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Company;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class WebAuthController extends Controller
{
    /**
     * Show login form.
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('web.dashboard');
        }
        return view('auth.login');
    }

    /**
     * Handle login request.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Redirect admins to admin dashboard
            if (Auth::user()->hasRole('admin')) {
                return redirect()->intended(route('admin.dashboard'));
            }
            
        // Redirect clients to client dashboard
        if (Auth::user()->hasRole('client')) {
            return redirect()->intended(route('client.dashboard'));
        }
        
        // Redirect employees to employee dashboard
        if (Auth::user()->hasRole('employee')) {
            return redirect()->intended(route('employee.dashboard'));
        }
        
        return redirect()->intended(route('web.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show registration form.
     */
    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('web.dashboard');
        }
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,client,employee',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $role = Role::where('name', $request->role)->first();
        if (!$role) {
            return redirect()->back()
                ->withErrors(['role' => 'Invalid role selected.'])
                ->withInput();
        }

        $user = DB::transaction(function () use ($request, $role) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $role->id,
            ]);

            if ($role->name === 'employee') {
                $this->createOrLinkEmployee($user, $request->name, $request->email);
            }

            return $user;
        });

        Auth::login($user);

        // Redirect admins to admin dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Registration successful! Welcome to DIY Payroll.');
        }

        // Redirect clients to client dashboard
        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard')
                ->with('success', 'Registration successful! Welcome to DIY Payroll.');
        }

        // Redirect employees to employee dashboard
        if ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard')
                ->with('success', 'Registration successful! Welcome to DIY Payroll.');
        }

        return redirect()->route('web.dashboard')
            ->with('success', 'Registration successful! Welcome to DIY Payroll.');
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('web.login')
            ->with('success', 'You have been logged out successfully.');
    }

    /**
     * Show dashboard.
     */
    public function dashboard()
    {
        // Redirect admins to admin dashboard
        if (Auth::user()->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect clients to client dashboard
        if (Auth::user()->hasRole('client')) {
            return redirect()->route('client.dashboard');
        }
        
        // Redirect employees to employee dashboard
        if (Auth::user()->hasRole('employee')) {
            return redirect()->route('employee.dashboard');
        }
        
        return view('dashboard');
    }

    private function createOrLinkEmployee(User $user, string $name, string $email): void
    {
        $employee = Employee::where('email', $email)->first();

        if ($employee) {
            if (!$employee->user_id) {
                $employee->update(['user_id' => $user->id]);
            }

            return;
        }

        [$firstName, $lastName] = $this->splitName($name);
        $company = Company::query()->first();

        if (!$company) {
            $company = Company::create([
                'name' => "{$firstName} Test Company",
                'email' => $email,
            ]);
        }

        Employee::create([
            'company_id' => $company->id,
            'user_id' => $user->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $email,
            'employment_type' => 'full_time',
            'pay_type' => 'hourly',
            'hourly_rate' => 0,
            'is_active' => true,
        ]);
    }

    private function splitName(string $name): array
    {
        $name = trim($name);

        if ($name === '') {
            return ['Employee', 'User'];
        }

        $parts = preg_split('/\s+/', $name) ?: [];
        $firstName = $parts[0] ?? 'Employee';
        $lastName = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : 'User';

        return [$firstName, $lastName];
    }
}
