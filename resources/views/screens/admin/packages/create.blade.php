@section('title', 'Create Package')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form class="card ajax-form" id="create-package-form" action="{{ route('packages.store') }}" method="POST">
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
                            <input class="form-control" id="title" type="text" name="title" required />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="price">Price</label>
                            <input class="form-control" id="price" type="number" name="price" min="0" step="0.01" required />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="currency">Currency</label>
                            <input class="form-control" id="currency" type="text" name="currency" value="USD" maxlength="3" required />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="billing_label">Billing label</label>
                            <input class="form-control" id="billing_label" type="text" name="billing_label" placeholder="/Flat yearly Fee" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="billing_cycle">Billing cycle</label>
                            <select class="form-select" id="billing_cycle" name="billing_cycle" required>
                                <option value="yearly" selected>Yearly</option>
                                <option value="monthly">Monthly</option>
                                <option value="one_time">One time</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">
                        <div class="form-group mb-3">
                            <label class="form-label" for="sort_order">Sort order</label>
                            <input class="form-control" id="sort_order" type="number" name="sort_order" min="0" value="0" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="cta_label">Button label</label>
                            <input class="form-control" id="cta_label" type="text" name="cta_label" value="Start Today" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label" for="quickbooks_item_id">QuickBooks item ID</label>
                            <input class="form-control" id="quickbooks_item_id" type="text" name="quickbooks_item_id" placeholder="Product / Service ID from QuickBooks" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label class="form-label" for="features_text">Features (one per line)</label>
                            <textarea class="form-control" id="features_text" name="features_text" rows="5" placeholder="No per-employee fees&#10;Unlimited runs..."></textarea>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <input type="hidden" name="is_active" value="0" />
                            <input class="form-check-input mt-0" type="checkbox" name="is_active" id="is_active" value="1" checked />
                            <label class="form-check-label mb-0" for="is_active">Show on website home page</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('packages.index') }}" class="btn btn-light me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    ajaxCreate();
</script>
@endpush
@endsection
