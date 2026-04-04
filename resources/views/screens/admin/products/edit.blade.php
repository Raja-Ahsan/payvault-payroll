@section('title', 'Edit Product')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <form class="card ajax-form" id="submit-form" method="POST" action="{{ route('products.update', $product->slug) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
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
                                    name="name" value="{{ old('name', $product->name) }}" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                                {{-- <input class="form-control" id="category"
                                type="text" placeholder="Enter Category" name="category" /> --}}
                                <select class="form-select" name="category_id">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ?? 'Selected' }}>{{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="price">Price <span class="text-danger">*</span></label>
                                <input class="form-control" id="price" type="text" placeholder="Enter Price"
                                    name="price" value="{{ old('price', $product->price) }}" />
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="stock">Stock Quantity</label>
                                <input class="form-control" id="stock" type="number" placeholder="Enter Stock Quantity"
                                    name="quantity" value="{{ old('quantity', $product->quantity) }}" />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="image">Product Image</label>
                                <input class="form-control mb-3" id="image" type="file" accept="image/*"
                                    name="image" value="{{ asset('/storage/' . $product->image) }}" />
                                <img id="imagePreview" class="image-preview"
                                    src="{{ asset('/storage/' . $product->image) }}" alt="Current Image"
                                    style="height: 150px; width: 150px;">


                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label">Gallery Images (Optional)</label>

                                <div id="gallery_images" class="dropzone"></div>

                                <input type="file" name="images[]" id="galleryInput" multiple hidden>
                            </div>

                            <div class="d-flex flex-wrap gap-2 mt-2" id="galleryPreview">
                                @foreach ($product->images as $img)
                                    <div class="gallery-item position-relative" data-id="{{ $img->id }}">
                                        <img src="{{ asset('storage/' . $img->images) }}"
                                            style="width:100px;height:100px;object-fit:cover">
                                        <button type="button" class="btn btn-danger btn-sm delete-gallery-image"
                                            style="position:absolute;top:2px;right:2px;padding:2px 6px;">
                                            ×
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" rows="3" placeholder="Enter Product Description"
                                    name="description">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <button class="btn btn-primary" id="submit-btn" type="submit">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        ajaxCreate("{{ route('products.index') }}");
        window.existingGalleryCount = {{ $product->images->count() }};
        Dropzone.autoDiscover = false;
        let deleteUrl = "{{ url('admin/products/gallery-image') }}";
        $(document).ready(function() {
            let maxTotal = 5;
            window.existingCount = window.existingGalleryCount || 0;
            window.allowedNew = maxTotal - window.existingCount;
            window.myDropzone = new Dropzone("#gallery_images", {
                url: "javascript:void(0)",
                autoProcessQueue: false,
                maxFiles: allowedNew,
                acceptedFiles: 'image/*',
                addRemoveLinks: true,
                clickable: true,

                init: function() {
                    let dz = this;

                    if (allowedNew <= 0) {
                        dz.disable();
                        $('#gallery_images').addClass('disabled');
                    }


                    dz.on("addedfile", function(file) {
                        file._dzId = Date.now() + Math.random();
                        let input = document.getElementById("galleryInput");
                        let dt = new DataTransfer();

                        Array.from(input.files).forEach(f => dt.items.add(f));
                        dt.items.add(file);

                        input.files = dt.files;
                    });

                    dz.on("removedfile", function(file) {
                        let input = document.getElementById("galleryInput");
                        let dt = new DataTransfer();

                        Array.from(input.files).forEach(f => {
                            if (f !== file) {
                                dt.items.add(f);
                            }
                        });

                        input.files = dt.files;
                    });

                    dz.on("maxfilesexceeded", function() {
                        Swal.fire({
                            icon: "error",
                            title: "You can upload a maximum of 5 gallery images."
                        });
                    });
                }
            });

        });

        $(document).on('click', '.delete-gallery-image', function() {
            let btn = $(this);
            let wrapper = btn.closest('.gallery-item');
            let imageId = wrapper.data('id');

            Swal.fire({
                title: "Remove image?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, remove"
            }).then((result) => {

                if (!result.isConfirmed) return;

                $.ajax({
                    url: deleteUrl + '/' + imageId,
                    type: "DELETE",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(res) {

                        if (res.success) {
                            wrapper.remove();

                            // 🔥 UPDATE COUNTS
                            existingCount--;
                            allowedNew++;

                            myDropzone.options.maxFiles = allowedNew;
                            myDropzone.enable();

                            Swal.fire({
                                icon: "success",
                                title: res.message,
                                timer: 1200,
                                showConfirmButton: false
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
