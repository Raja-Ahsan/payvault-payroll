<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $packageId = request()->query('package', old('package'));
        $package = null;
        if ($packageId) {
            $package = Package::query()
                ->whereKey((int) $packageId)
                ->where('is_active', true)
                ->first();
        }

        return view('auth.register', compact('package'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'package' => ['nullable', 'integer', 'exists:packages,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Role::firstOrCreate(
            ['name' => config('roles.client'), 'guard_name' => 'web'],
        );
        $user->assignRole(config('roles.client'));

        event(new Registered($user));

        Auth::login($user);

        $selectedPackage = null;
        if ($request->filled('package')) {
            $selectedPackage = Package::query()
                ->whereKey((int) $request->input('package'))
                ->where('is_active', true)
                ->first();
        }

        if ($selectedPackage) {
            return redirect()->route('packages.checkout.show', $selectedPackage);
        }

        return redirect(route('dashboard', absolute: false));
    }
}
