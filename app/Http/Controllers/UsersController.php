<?php

namespace App\Http\Controllers;

use App\Models\PackageSubscription;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        $authUser = current_user();

        $users = User::with(['roles', 'packageSubscriptions' => function ($q) {
            $q->with('package')->orderByDesc('created_at');
        }])
            ->where('id', '!=', $authUser->id)
            ->paginate(10);

        return view('screens.admin.users.index', compact('users'));
    }

    public function show(User $user)
    {
        $user->load(['roles', 'packageSubscriptions.package']);

        return view('screens.admin.users.show', compact('user'));
    }

    public function packageSubscriptions()
    {
        $subscriptions = PackageSubscription::query()
            ->with(['user', 'package'])
            ->orderByDesc('created_at')
            ->paginate(20);

        return view('screens.admin.users.subscriptions-index', compact('subscriptions'));
    }

    public function create()
    {
        return view('screens.admin.users.create');
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'profile_img' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);
        

        $profile_Image = null;
        if ($request->hasFile('profile_img')) {
            $profile_Image = $request->file('profile_img')->store('profiles', 'public');
        }
        $validated['profile_img'] = $profile_Image;
        $user = User::create($validated);
        if ($user->hasRole(config('roles.user'))) {
            return redirect()->route('users.index')->with('success', 'User created successfully');
        }
    }
}
