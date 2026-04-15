@extends('layouts.web.master')

@section('content')
<main class="py-16 md:py-24">
    <div class="container max-w-lg">
        <h1 class="text-[var(--text-secondary-color)] text-2xl md:text-3xl font-bold mb-2">Confirm your package</h1>
        <p class="text-[var(--text-color)] opacity-80 mb-8">Review details below. Subscriptions are recorded for your account; connect payment gateways later for live charges.</p>

        <div class="pricing-card group mb-8">
            <div class="pricing-card-top">
                <h3 class="pricing-title">{{ $package->title }}</h3>
                <div class="mb-6">
                    <span class="pricing-price-val">
                        @if (strtoupper($package->currency) === 'USD')
                            ${{ number_format((float) $package->price, 0) }}
                        @else
                            {{ $package->currency }} {{ number_format((float) $package->price, 2) }}
                        @endif
                        @if ($package->billing_label)
                            <span class="pricing-price-unit">{{ $package->billing_label }}</span>
                        @endif
                    </span>
                </div>
            </div>
            <div class="pricing-card-bottom">
                <p class="font-bold text-[16px] mb-2 feature-text">Includes:</p>
                <ul class="space-y-3">
                    @forelse ($package->features ?? [] as $line)
                        <li class="flex items-start space-x-3">
                            <img src="{{ asset('images/check-icon.png') }}" alt="" class="check-icon">
                            <span class="feature-text">{{ $line }}</span>
                        </li>
                    @empty
                        <li class="feature-text text-muted">No feature lines configured for this package.</li>
                    @endforelse
                </ul>
                @if ($package->quickbooks_item_id)
                    <p class="text-sm mt-4 opacity-70">QuickBooks item ID: <code>{{ $package->quickbooks_item_id }}</code></p>
                @endif
            </div>
        </div>

        <form method="POST" action="{{ route('packages.checkout.store', $package) }}" class="space-y-4">
            @csrf
            <button type="submit" class="pricing-card-btn text-white w-full text-center border-0 cursor-pointer">
                Activate subscription
            </button>
            <a href="{{ route('home') }}" class="block text-center text-[var(--text-secondary-color)] underline text-sm">Back to home</a>
        </form>
    </div>
</main>
@endsection
