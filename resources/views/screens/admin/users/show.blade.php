@section('title', 'User: '.$user->name)
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12 col-lg-5 mb-4">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Profile</h5>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><span class="c-o-light f-w-600">Name:</span> {{ $user->name }}</p>
                        <p class="mb-2"><span class="c-o-light f-w-600">Email:</span> {{ $user->email }}</p>
                        <p class="mb-2"><span class="c-o-light f-w-600">Role:</span>
                            {{ $user->roles->pluck('name')->join(', ') ?: '—' }}</p>
                        <p class="mb-0"><span class="c-o-light f-w-600">Joined:</span> {{ $user->created_at->format('d M Y, H:i A') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-12 col-lg-7 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Active subscription</h5>
                    </div>
                    <div class="card-body">
                        @php $active = $user->packageSubscriptions->firstWhere('status', \App\Models\PackageSubscription::STATUS_ACTIVE); @endphp
                        @if ($active)
                            <p class="mb-1"><span class="c-o-light f-w-600">Package:</span> {{ $active->package?->title ?? '—' }}</p>
                            <p class="mb-1"><span class="c-o-light f-w-600">Amount:</span> {{ $active->currency }} {{ number_format((float) $active->amount_paid, 2) }}</p>
                            <p class="mb-1"><span class="c-o-light f-w-600">Period:</span>
                                {{ $active->starts_at?->format('M j, Y') ?? '—' }}
                                —
                                {{ $active->ends_at?->format('M j, Y') ?? 'Open-ended' }}</p>
                            <p class="mb-0"><span class="c-o-light f-w-600">QuickBooks item ID:</span> {{ $active->quickbooks_item_id ?: '—' }}</p>
                        @else
                            <p class="text-muted mb-0">No active package subscription.</p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Package purchase history</h5>
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
                                    @forelse ($user->packageSubscriptions as $sub)
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
                                            <td colspan="6" class="text-center text-muted py-4">No subscription history.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-end">
                        <a href="{{ route('users.index') }}" class="btn btn-light">Back to users</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
