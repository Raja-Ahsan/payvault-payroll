@section('title', 'My subscription')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Current plan</h5>
                    </div>
                    <div class="card-body">
                        @if ($activeSubscription)
                            <p class="mb-2"><span class="c-o-light f-w-600">Package:</span> {{ $activeSubscription->package?->title ?? '—' }}</p>
                            <p class="mb-2"><span class="c-o-light f-w-600">Status:</span>
                                <span class="badge badge-light-success text-uppercase">{{ $activeSubscription->status }}</span>
                            </p>
                            <p class="mb-2"><span class="c-o-light f-w-600">Amount:</span>
                                {{ $activeSubscription->currency }} {{ number_format((float) $activeSubscription->amount_paid, 2) }}
                            </p>
                            <p class="mb-2"><span class="c-o-light f-w-600">Period:</span>
                                {{ $activeSubscription->starts_at?->format('M j, Y') ?? '—' }}
                                —
                                {{ $activeSubscription->ends_at?->format('M j, Y') ?? 'Open-ended' }}
                            </p>
                            <p class="mb-0"><span class="c-o-light f-w-600">QuickBooks item ID:</span>
                                {{ $activeSubscription->quickbooks_item_id ?: '—' }}</p>
                        @else
                            <p class="text-muted mb-3">You do not have an active subscription yet.</p>
                            <a href="{{ route('home') }}#pricing" class="btn btn-primary">View packages</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Package features</h5>
                    </div>
                    <div class="card-body">
                        @if ($activeSubscription?->package)
                            <ul class="list-unstyled mb-0">
                                @forelse ($activeSubscription->package->features ?? [] as $line)
                                    <li class="d-flex align-items-start mb-2">
                                        <i class="fa-solid fa-check text-success me-2 mt-1"></i>
                                        <span>{{ $line }}</span>
                                    </li>
                                @empty
                                    <li class="text-muted">No feature list for this package.</li>
                                @endforelse
                            </ul>
                        @else
                            <p class="text-muted mb-0">Subscribe to a package to see included features here.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Purchase history</h5>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Package</span></th>
                                        <th><span class="c-o-light f-w-600">Status</span></th>
                                        <th><span class="c-o-light f-w-600">Amount</span></th>
                                        <th><span class="c-o-light f-w-600">Starts</span></th>
                                        <th><span class="c-o-light f-w-600">Ends</span></th>
                                        <th><span class="c-o-light f-w-600">QuickBooks ID</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($subscriptions as $sub)
                                        <tr>
                                            <td>{{ $sub->package?->title ?? '—' }}</td>
                                            <td><span class="badge badge-light-secondary text-uppercase">{{ $sub->status }}</span></td>
                                            <td>{{ $sub->currency }} {{ number_format((float) $sub->amount_paid, 2) }}</td>
                                            <td>{{ $sub->starts_at?->format('M j, Y') ?? '—' }}</td>
                                            <td>{{ $sub->ends_at?->format('M j, Y') ?? '—' }}</td>
                                            <td>{{ $sub->quickbooks_item_id ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">No purchases yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
