@section('title', 'Show Product')
@extends('layouts.admin.master')
@section('content')
    <div class="container-fluid">
        <div class="edit-profile">
            <form class="card" id="submit-form" method="POST" action="{{ route('products.update', $product->slug) }}"
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
                                    name="name" value="{{ old('name', $product->name) }}" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                                {{-- <input class="form-control" id="category"
                                type="text" placeholder="Enter Category" name="category" /> --}}
                                <select class="form-select" name="category_id" disabled>
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
                                    name="price" value="{{ old('price', $product->price) }}" readonly/>
                            </div>
                        </div>
                        <div class="col-sm-6 col-md-6">
                            <div class="mb-3">
                                <label class="form-label" for="stock">Stock Quantity</label>
                                <input class="form-control" id="stock" type="number" placeholder="Enter Stock Quantity"
                                    name="quantity" value="{{ old('quantity', $product->quantity) }}" readonly/>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label mb-3 d-block" for="image">Product Image</label>
                                <img id="imagePreview" class="image-preview"  src="{{ asset('/storage/' . $product->image) }}" alt="Current Image"
                                    style="height: 150px; width: 150px;">


                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-3">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" rows="3" placeholder="Enter Product Description"
                                    readonly name="description">{{ old('description', $product->description) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
