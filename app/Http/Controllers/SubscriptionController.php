<?php

namespace App\Http\Controllers;

use App\Models\PackageSubscription;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $subscriptions = $user->packageSubscriptions()
            ->with('package')
            ->orderByDesc('created_at')
            ->get();

        $activeSubscription = $subscriptions->firstWhere('status', PackageSubscription::STATUS_ACTIVE);

        return view('screens.admin.subscription.index', compact('subscriptions', 'activeSubscription'));
    }
}
