@section('title', 'Create Income Category')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form class="card ajax-form" id="submit-form"  action="{{route('categories.income.store')}}" method="POST">
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
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="name">Title</label>
                            <input class="form-control" id="title"
                                type="text" placeholder="Enter Title" name="title" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="abbreviation">Abbreviation</label>
                            <input class="form-control" id="abbreviation"
                                type="text" placeholder="Enter Abbreviation" name="abbreviation" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label for="name">Type</label>
                            <select class="form-select" id="income_type_id" name="income_type_id" required>
                                @forelse ($incomeTypes ?? [] as $type)
                                <option value="{{ $type->id }}">{{ $type->title }}</option>
                                @empty
                                <option value="" disabled selected>No income types available</option>
                                @endforelse
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="w2_box_12">W-2 Box 12 Code</label>
                            <input class="form-control" id="w2_box_12"
                                type="text" placeholder="Enter W-2 Box 12 Code" name="w2_box_12_code" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="w2_box_14">W-2 Box 14 Abbreviation</label>
                            <input class="form-control" id="w2_box_14"
                                type="text" placeholder="Enter W-2 Box 14 Abbreviation" name="w2_box_14_abbreviation" />
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <input
                                name="reported_tips"
                                class="form-check-input mt-0"
                                id="reported_tips"
                                type="checkbox"
                                value="1" />
                            <label
                                class="form-check-label mb-0"
                                for="reported_tips">Reported Tips (include in "Social security tips" on form W-2)</label>
                        </div>
                        <div class="form-group mb-3">
                            <input
                                name="omit_net_pay"
                                class="form-check-input mt-0"
                                id="omit_net_pay"
                                type="checkbox"
                                value="1" />
                            <label
                                class="form-check-label mb-0"
                                for="omit_net_pay">Omit from Net Pay</label>
                        </div>
                        <div class="form-group mb-3">
                            <input
                                name="inactive"
                                class="form-check-input mt-0"
                                id="inactive"
                                type="checkbox"
                                value="1" />
                            <label
                                class="form-check-label mb-0"
                                for="inactive">Inactive</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <h2 class="mb-4">Taxes Applied</h2>
                                @forelse ($taxCategories ?? [] as $tax)
                                    <div class="form-group mb-3">
                                        <input
                                            class="form-check-input mt-0"
                                            type="checkbox"
                                            value="{{ $tax->id }}"
                                            id="tax_category_{{ $tax->id }}"
                                            name="tax_category_ids[]"
                                            checked
                                            />
                                        <label class="form-check-label mb-0" for="tax_category_{{ $tax->id }}">{{ $tax->title }}</label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                            <div class="col-lg-6">
                                <h2 class="mb-4">Deductions Applied</h2>
                                @forelse ($deductionCategories ?? [] as $deduction)
                                    <div class="form-group mb-3">
                                        <input
                                            class="form-check-input mt-0"
                                            type="checkbox"
                                            value="{{ $deduction->id }}"
                                            id="deduction_category_{{ $deduction->id }}"
                                            name="deduction_category_ids[]"
                                            checked
                                            />
                                        <label class="form-check-label mb-0" for="deduction_category_{{ $deduction->id }}">{{ $deduction->title }}</label>
                                    </div>
                                @empty
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <button class="btn btn-primary" type="submit">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    ajaxCreate("{{ route('categories.income.index') }}");
</script>
@endpush
@endsection