@section('title', 'Edit Package')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form
            class="card"
            id="package-edit-form"
            method="POST"
            action="{{ route('packages.update', $package) }}">
            @csrf
            <div class="card-header">
                <div class="card-options">
                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row custom-input">
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" id="title" type="text" name="title" required
                                value="{{ old('title', $package->title) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input class="form-control" id="price" type="number" name="price" min="0" step="0.01" required
                                value="{{ old('price', $package->price) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="currency">Currency</label>
                            <input class="form-control" id="currency" type="text" name="currency" maxlength="3" required
                                value="{{ old('currency', $package->currency) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="billing_label">Billing label</label>
                            <input class="form-control" id="billing_label" type="text" name="billing_label"
                                value="{{ old('billing_label', $package->billing_label) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="billing_cycle">Billing cycle</label>
                            <select class="form-select" id="billing_cycle" name="billing_cycle" required>
                                @foreach (['yearly' => 'Yearly', 'monthly' => 'Monthly', 'one_time' => 'One time'] as $value => $label)
                                    <option value="{{ $value }}" @selected(old('billing_cycle', $package->billing_cycle) === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="sort_order">Sort order</label>
                            <input class="form-control" id="sort_order" type="number" name="sort_order" min="0"
                                value="{{ old('sort_order', $package->sort_order) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="cta_label">Button label</label>
                            <input class="form-control" id="cta_label" type="text" name="cta_label"
                                value="{{ old('cta_label', $package->cta_label) }}" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="quickbooks_item_id">QuickBooks item ID</label>
                            <input class="form-control" id="quickbooks_item_id" type="text" name="quickbooks_item_id"
                                value="{{ old('quickbooks_item_id', $package->quickbooks_item_id) }}" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="features_text">Features (one per line)</label>
                            <textarea class="form-control" id="features_text" name="features_text" rows="5">{{ old('features_text', $featuresText ?? '') }}</textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <input type="hidden" name="is_active" value="0" />
                            <input class="form-check-input mt-0" type="checkbox" name="is_active" id="is_active" value="1"
                                @checked(filter_var(old('is_active', $package->is_active), FILTER_VALIDATE_BOOLEAN)) />
                            <label class="form-check-label mb-0" for="is_active">Show on website home page</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('packages.index') }}" class="btn btn-light me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Update</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    ajaxUpdate('#package-edit-form', "{{ route('packages.index') }}");
</script>
@endpush
@endsection
