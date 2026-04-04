@section('title', 'Create Products Categories')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <form class="card ajax-form" id="submit-form" action="{{ route('product-categories.store') }}"
                method="POST">
                @csrf
                <div class="card-header">
                    <div class="card-options">
                        <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i
                                class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#"
                            data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row custom-input">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="category_name">Category Name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="category_name" type="text"
                                    placeholder="Enter Service Name" name="name" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label" for="slug">Slug <span
                                        class="text-danger"></span></label>
                                <input class="form-control" id="slug" type="text"
                                    placeholder="Enter Slug" name="slug" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" id="submit-btn" type="submit">
                        Create
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
    ajaxCreate("{{ route('product-categories.index') }}");
    </script>
@endpush
