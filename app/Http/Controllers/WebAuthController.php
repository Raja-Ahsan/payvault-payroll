<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        Auth::login($user);

        // Redirect admins to admin dashboard
        if ($user->hasRole('admin')) {
            return redirect()->route('admin.dashboard')
                ->with('success', 'Registration successful! Welcome to PayVault Payroll.');
        }

        // Redirect clients to client dashboard
        if ($user->hasRole('client')) {
            return redirect()->route('client.dashboard')
                ->with('success', 'Registration successful! Welcome to PayVault Payroll.');
        }

        // Redirect employees to employee dashboard
        if ($user->hasRole('employee')) {
            return redirect()->route('employee.dashboard')
                ->with('success', 'Registration successful! Welcome to PayVault Payroll.');
        }

        return redirect()->route('web.dashboard')
            ->with('success', 'Registration successful! Welcome to PayVault Payroll.');
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
}
