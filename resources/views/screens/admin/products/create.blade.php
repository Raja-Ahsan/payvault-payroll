@section('title', 'Create Product')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <form class="card ajax-form" id="submit-form" method="POST" action="{{ route('products.store') }}"
                enctype="multipart/form-data">
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
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="name">Product Name <span
                                        class="text-danger">*</span></label>
                                <input class="form-control" id="name" type="text" placeholder="Enter Product Name"
                                    name="name" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                                {{-- <input class="form-control" id="category"
                                type="text" placeholder="Enter Category" name="category" /> --}}
                                <select class="form-select" name="category_id">
                                    <option selected>Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                                <input class="form-control" id="price" type="text" placeholder="Enter Price"
                                    name="price" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="stock">Stock Quantity</label>
                                <input class="form-control" id="stock" type="number" placeholder="Enter Stock Quantity"
                                    name="quantity" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="image">Product Image</label>
                                <input class="form-control" id="image" type="file" accept="image/*" name="image" />
                                <div class="mt-3">
                                    <img id="imagePreview" class="image-preview"
                                        src="{{ asset('images/placeholders/img-not-available.png') }}" alt="Preview"
                                        style="width: 100px; height: 100px; display: none;">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Gallery Images (Optional)</label>
                                <div id="gallery_images" class="dropzone"></div> <!-- Dropzone.js container -->
                                <input type="file" name="images[]" id="galleryInput" multiple hidden>
                            </div>
                            <div class="d-flex flex-wrap gap-2 mt-2" id="galleryPreview"></div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" rows="3" placeholder="Enter Product Description"
                                    name="description"></textarea>
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
    {{-- @include('includes.ajax-requests.create-ajax'); --}}
    <script>
        ajaxCreate("{{ route('products.index') }}");
        Dropzone.autoDiscover = false;

        $(document).ready(function() {

            let myDropzone = new Dropzone("#gallery_images", {
                url: "javascript:void(0)",
                autoProcessQueue: false,
                maxFiles: 5,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                clickable: true,

                init: function() {
                    let dz = this;

                    this.on("maxfilesexceeded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "You can upload a maximum of 5 gallery images.",
                            showConfirmButton: true
                        });
                    });

                    dz.on("addedfile", function(file) {
                        let input = document.getElementById("galleryInput");
                        let dt = new DataTransfer();

                        // pehle se selected files
                        Array.from(input.files).forEach(f => dt.items.add(f));

                        // nayi file
                        dt.items.add(file);

                        input.files = dt.files;
                    });

                    dz.on("removedfile", function(file) {
                        let input = document.getElementById("galleryInput");
                        let dt = new DataTransfer();

                        Array.from(input.files).forEach(f => {
                            if (f.name !== file.name) dt.items.add(f);
                        });

                        input.files = dt.files;
                    });
                }
            });

        });
    </script>
@endpush
