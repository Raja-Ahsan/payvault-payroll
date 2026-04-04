@section('title', 'All Products')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" href="{{ route('products.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Add
                                Product</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="list-product user-list-table">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table dataTable" id="products-table">
                                    <thead>
                                        <tr>
                                            <th><span class="c-o-light f-w-600">Product Name</span></th>
                                            <th><span class="c-o-light f-w-600">Image</span></th>
                                            <th><span class="c-o-light f-w-600">Category</span></th>
                                            <th><span class="c-o-light f-w-600">Price</span></th>
                                            <th><span class="c-o-light f-w-600">Quantity</span></th>
                                            <th><span class="c-o-light f-w-600">Status</span></th>
                                            <th><span class="c-o-light f-w-600">Action</span></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $row)
                                            <tr>
                                                <td>{{ $row->name }}</td>
                                                <td>
                                                    @if ($row->image)
                                                        <img src="{{ asset('storage/' . $row->image) }}" width="50">
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{ $row->category->name ?? '-' }}</td>
                                                <td>{{ $row->price }}</td>
                                                <td>{{ $row->quantity }}</td>
                                                <td>
                                                    @if ($row->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="common-align gap-2 justify-content-start">

                                                        <!-- view -->
                                                        <a class="square-white"
                                                            href="{{ route('products.show', $row->slug) }}">
                                                            <span><i class="fa-solid fa-eye"></i></span>
                                                        </a>

                                                        <!-- edit -->
                                                        <a class="square-white"
                                                            href="{{ route('products.edit', $row->slug) }}">
                                                            <span><i class="fa-solid fa-pen"></i></span>
                                                        </a>

                                                        <!-- delete -->
                                                        <form action="{{ route('products.destroy', $row->slug) }}"
                                                            method="POST" style="display:inline">
                                                            @csrf
                                                            @method('DELETE')

                                                            <button data-delete="true"
                                                                data-url="{{ route('products.destroy', $row->slug) }}"
                                                                type="button" class="square-white delete-btn">
                                                                <span><i class="fa-solid fa-trash"></i></span>
                                                            </button>
                                                        </form>

                                                        <!-- toggle -->
                                                        <div
                                                            class="form-check form-switch form-check-inline custom-switch product-toggle-wrapper">
                                                            <input type="checkbox"
                                                                class="toggle-status form-check-input switch-primary check-size"
                                                                data-id="{{ $row->id }}"
                                                                data-url="{{ route('products.toggleActive') }}"
                                                                data-title-on="Activate item?"
                                                                data-title-off="Deactivate item?"
                                                                {{ $row->is_active ? 'checked' : '' }}>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
    @include('includes.ajax-requests.delete')
    <script src="{{ asset('assets/js/toggle-status.js') }}"></script>
    <script>
        var table = $('#products-table').DataTable({
            order: [
                [3, 'desc']
            ],
            columnDefs: [{
                orderable: false,
                targets: 4
            }]
        });
    </script>
@endpush
