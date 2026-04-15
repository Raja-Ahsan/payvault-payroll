<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\PackageSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PackagePurchaseController extends Controller
{
    public function show(Package $package): RedirectResponse|View
    {
        if (! $package->is_active) {
            abort(404);
        }

        return view('screens.web.packages.checkout', compact('package'));
    }

    public function store(Request $request, Package $package): RedirectResponse
    {
        if (! $package->is_active) {
            abort(404);
        }

        $user = $request->user();

        $endsAt = $this->computeEndDate(Carbon::now(), $package->billing_cycle);

        DB::transaction(function () use ($user, $package, $endsAt) {
            PackageSubscription::query()
                ->where('user_id', $user->id)
                ->active()
                ->update(['status' => PackageSubscription::STATUS_SUPERSEDED]);

            PackageSubscription::create([
                'user_id' => $user->id,
                'package_id' => $package->id,
                'status' => PackageSubscription::STATUS_ACTIVE,
                'amount_paid' => $package->price,
                'currency' => $package->currency,
                'starts_at' => now(),
                'ends_at' => $endsAt,
                'quickbooks_item_id' => $package->quickbooks_item_id,
                'payment_reference' => null,
                'notes' => 'Recorded in-app. Connect Stripe or QuickBooks payments for live billing.',
            ]);
        });

        return redirect()
            ->route('admin.dashboard')
            ->with('success', 'Your package subscription is now active.');
    }

    protected function computeEndDate(Carbon $start, string $billingCycle): ?Carbon
    {
        return match ($billingCycle) {
            'yearly' => $start->copy()->addYear(),
            'monthly' => $start->copy()->addMonth(),
            default => null,
        };
    }
}
