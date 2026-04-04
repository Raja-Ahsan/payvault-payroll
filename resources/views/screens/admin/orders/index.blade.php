@section('title', 'All Orders')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border">
                        <h5>All Orders</h5>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="list-product user-list-table">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table" id="orders-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="c-o-light f-w-600">Order ID</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Total Amount</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Order Status</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Order Date</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $order)
                                            <tr class="product-removes inbox-data">
                                                <td><a
                                                        href="{{ route('orders.show', $order) }}">#ORD-{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</a>
                                                </td>
                                                <td>
                                                    <p>${{ number_format($order->total, 2) }}</p>
                                                </td>
                                                <td>
                                                    @php
                                                        $canUpdateStatus = current_user()->hasRole(
                                                            config('roles.league_contractor'),
                                                        );
                                                    @endphp

                                                    @if ($canUpdateStatus)
                                                        <select class="form-select form-select-sm order-status-select"
                                                            data-order-id="{{ $order->id }}"
                                                            data-current-status="{{ $order->order_status }}">
                                                            @foreach (['pending', 'processing', 'shipped', 'delivered', 'completed', 'cancelled'] as $status)
                                                                <option value="{{ $status }}"
                                                                    @selected($order->order_status === $status)>
                                                                    {{ ucfirst($status) }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        @php
                                                            $statusBadge = match ($order->order_status) {
                                                                'pending', 'pending' => 'badge-light-info',
                                                                'processing', 'processing' => 'badge-light-info',
                                                                'shipped', 'shipped' => 'badge-light-info',
                                                                'delivered', 'delivered' => 'badge-light-success',
                                                                'completed', 'completed' => 'badge-light-success',
                                                                'cancelled', 'cancelled' => 'badge-light-danger',
                                                                default => 'badge-light-secondary',
                                                            };
                                                        @endphp
                                                        <span class="badge {{ $statusBadge }}">
                                                            {{ ucfirst($order->order_status) }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p>{{ $order->created_at->format('d M Y, h:i A') }}</p>
                                                </td>
                                                <td>
                                                    <div class="common-align gap-2 justify-content-start">
                                                        <a class="square-white" href="{{ route('orders.show', $order) }}"
                                                            title="View Order">
                                                            <span><i class="fa-solid fa-eye"></i></span>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">No orders found</td>
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
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.order-status-select').forEach(select => {

                select.addEventListener('change', function() {

                    let orderId = this.dataset.orderId;
                    let newStatus = this.value;
                    let oldStatus = this.dataset.currentStatus;
                    let selectEl = this;

                    Swal.fire({
                        title: 'Change Order Status?',
                        text: `Are you sure you want to change status to "${newStatus}"?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, update',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {

                        if (result.isConfirmed) {

                            fetch(`/admin/orders/${orderId}/status`, {
                                    method: 'PATCH',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document
                                            .querySelector('meta[name="csrf-token"]')
                                            .getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        order_status: newStatus
                                    })
                                })
                                .then(async res => {
                                    const data = await res.json();

                                    if (!res.ok) {
                                        throw data;
                                    }

                                    return data;
                                })
                                .then(data => {
                                    selectEl.dataset.currentStatus = newStatus;

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Updated',
                                        text: data.message,
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                })
                                .catch(err => {
                                    selectEl.value = oldStatus;

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: err.message ??
                                            'Something went wrong'
                                    });
                                });

                        } else {
                            selectEl.value = oldStatus;
                        }

                    });
                });

            });

        });
    </script>
@endpush
