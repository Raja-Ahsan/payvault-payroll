@section('title', 'Package subscriptions')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h5 class="mb-0">Client package purchases</h5>
                        <p class="text-muted small mb-0">All subscription records across users (newest first).</p>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="table-responsive custom-scrollbar">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th><span class="c-o-light f-w-600">Client</span></th>
                                        <th><span class="c-o-light f-w-600">Email</span></th>
                                        <th><span class="c-o-light f-w-600">Package</span></th>
                                        <th><span class="c-o-light f-w-600">Status</span></th>
                                        <th><span class="c-o-light f-w-600">Amount</span></th>
                                        <th><span class="c-o-light f-w-600">Period</span></th>
                                        <th><span class="c-o-light f-w-600">QuickBooks ID</span></th>
                                        <th><span class="c-o-light f-w-600"></span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($subscriptions as $sub)
                                        <tr>
                                            <td>{{ $sub->user?->name ?? '—' }}</td>
                                            <td>{{ $sub->user?->email ?? '—' }}</td>
                                            <td>{{ $sub->package?->title ?? '—' }}</td>
                                            <td><span class="badge badge-light-secondary text-uppercase">{{ $sub->status }}</span></td>
                                            <td>{{ $sub->currency }} {{ number_format((float) $sub->amount_paid, 2) }}</td>
                                            <td class="text-nowrap small">
                                                {{ $sub->starts_at?->format('M j, Y') ?? '—' }}
                                                —
                                                {{ $sub->ends_at?->format('M j, Y') ?? '—' }}
                                            </td>
                                            <td>{{ $sub->quickbooks_item_id ?: '—' }}</td>
                                            <td>
                                                @if ($sub->user)
                                                    <a href="{{ route('users.show', $sub->user) }}" class="btn btn-light btn-sm">User detail</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center text-muted py-4">No subscriptions yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="px-3 pb-3">
                            {{ $subscriptions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
