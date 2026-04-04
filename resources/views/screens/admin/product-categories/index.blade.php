@section('title', 'All Products Categories')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid user-list-wrapper">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header card-no-border text-end">
                        <div class="card-header-right-icon">
                            <a class="btn btn-primary f-w-500" href="{{ route('product-categories.create') }}"><i
                                    class="fa-solid fa-plus pe-2"></i>Add
                                Product Category</a>
                        </div>
                    </div>
                    <div class="card-body pt-0 px-0">
                        <div class="list-product user-list-table">
                            <div class="table-responsive custom-scrollbar">
                                <table class="table" id="product-categories-table">
                                    <thead>
                                        <tr>
                                            <th>
                                                <span class="c-o-light f-w-600">Category Name</span>
                                            </th>
                                            <th>
                                                <span class="c-o-light f-w-600">Actions</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($productCategories as $productCategory)
                                            <tr class="product-removes inbox-data" data-slug="{{ $productCategory->slug }}">
                                                <td>
                                                    <p>{{ $productCategory->name }}</p>
                                                </td>
                                                <td>
                                                    <div class="common-align gap-2 justify-content-start">
                                                        <button class="square-white btn-edit"
                                                            data-slug="{{ $productCategory->slug }}"
                                                            data-name="{{ $productCategory->name }}">
                                                            <span><i class="fa-solid fa-pen"></i></span>
                                                        </button>
                                                        <form
                                                            action="{{ route('product-categories.destroy', $productCategory->slug) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="square-white ajax-delete">
                                                                <span><i class="fa-solid fa-trash"></i></span>
                                                            </button>
                                                        </form>
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
    <x-modals.crud-modal />
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/crud-modal.js') }}"></script>
    <script>
        var table = $('#product-categories-table').DataTable({
                order: [],
                columnDefs: [
                    { orderable: false, targets: 1 }
                ]
            });
        ajaxDelete('.ajax-delete', 'tr');
        ajaxUpdate('#crudForm', null, 'tr');

        function updateCategoryRow(category) {
            if (!currentEditingSlug) return;

            let row = $('tr[data-slug="' + currentEditingSlug + '"]');

            row.find('td:first p').text(category.name);

            row.find('.btn-edit')
                .attr('data-name', category.name)
                .attr('data-slug', category.slug);

            row.attr('data-slug', category.slug);

            // reset state
            currentEditingSlug = null;
        }


        let currentEditingSlug = null;

        $(document).on('click', '.btn-edit', function() {
            currentEditingSlug = $(this).data('slug');

            let name = $(this).data('name');

            openCrudModal({
                title: "Edit Product Category",
                button: "Update",
                url: "/admin/products/categories/" + currentEditingSlug,
                method: "PUT",
                fields: `
            <div class="mb-3">
                <label>Category Name</label>
                <input type="text" name="name" value="${name}" class="form-control">
            </div>
        `,
            });
        });
    </script>
@endpush
