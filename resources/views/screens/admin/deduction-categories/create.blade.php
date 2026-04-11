@section('title', 'Create Deduction Category')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <form class="card ajax-form" id="create-deduction-category-form" action="{{ route('categories.deduction.store') }}" method="POST">
            @csrf
            <div class="card-header">
                <div class="card-options">
                    <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a>
                    <a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
                </div>
            </div>
            <div class="card-body">
                <div class="row custom-input">

                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="title">Title</label>
                            <input class="form-control" id="title" type="text" name="title" required />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="abbreviation">Abbreviation</label>
                            <input class="form-control" id="abbreviation" type="text" name="abbreviation" />
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="calculation">Calculation</label>
                                <select class="form-select" id="income_type_id" name="income_type_id" required>
                                    @forelse (($incomeTypes ?? []) as $type)
                                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                                    @empty
                                    <option value="" disabled selected>No income types available</option>
                                    @endforelse
                                </select>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="paid_by">Paid by</label>
                            <select class="form-select" id="paid_by" name="paid_by" required>
                                @foreach ($paidByOptions ?? [] as $value => $label)
                                <option value="{{ $value }}" @selected($value==='employee' )>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label d-block mb-2">Default Quarterly Rates</label>
                        <div class="row g-2">
                            <div class="col-6 col-sm-3 col-md-2">
                                <input class="form-control" type="number" name="quarterly_rate_q1" id="quarterly_rate_q1" min="0" step="any" placeholder="Q1" />
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <input class="form-control" type="number" name="quarterly_rate_q2" id="quarterly_rate_q2" min="0" step="any" placeholder="Q2" />
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <input class="form-control" type="number" name="quarterly_rate_q3" id="quarterly_rate_q3" min="0" step="any" placeholder="Q3" />
                            </div>
                            <div class="col-6 col-sm-3 col-md-2">
                                <input class="form-control" type="number" name="quarterly_rate_q4" id="quarterly_rate_q4" min="0" step="any" placeholder="Q4" />
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-4">
                        <div class="form-group mb-3">
                            <label class="form-label" for="cutoff">Cutoff</label>
                            <input class="form-control" id="cutoff" type="number" name="cutoff" min="0" step="any" />
                            <small class="text-muted">Leave blank for no cutoff</small>
                        </div>
                    </div>
                    <div class="col-12">
                        <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3">
                            <legend class="float-none w-auto px-2 mb-2 fs-6">W-2 Options</legend>
                            <div class="row align-items-end">
                                <div class="col-md-4">
                                    <div class="form-group mb-3 mb-md-0">
                                        <input class="form-check-input mt-0" type="checkbox" name="use_w2_box_10" id="use_w2_box_10" value="1" />
                                        <label class="form-check-label mb-0" for="use_w2_box_10">Use on Box 10 (Dependent Care Benefits)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3 mb-md-0">
                                        <input class="form-check-input mt-0" type="checkbox" name="use_w2_box_12" id="use_w2_box_12" value="1" />
                                        <label class="form-check-label mb-0" for="use_w2_box_12">Use on Box 12</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3 mb-md-0">
                                        <label class="form-label" for="w2_box_12_code">W-2 Code (Box 12)</label>
                                        <input class="form-control" id="w2_box_12_code" type="text" name="w2_box_12_code" />
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <input class="form-check-input mt-0" type="checkbox" name="use_w2_box_14" id="use_w2_box_14" value="1" />
                                        <label class="form-check-label mb-0" for="use_w2_box_14">Use on Box 14 (Other)</label>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-12 mb-3">
                        <p class="small text-muted mb-1">Pre-tax payroll deductions reduce your employee&apos;s taxable wages.</p>
                        <p class="small text-muted mb-0">When you apply related taxes below, taxable wages for those taxes are reduced by this deduction amount.</p>
                    </div>
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <input class="form-check-input mt-0" type="checkbox" name="inactive" id="inactive" value="1" />
                            <label class="form-check-label mb-0" for="inactive">Inactive</label>
                        </div>
                    </div>
                    <div class="col-12">
                        <button type="button" class="btn btn-outline-secondary btn-sm" disabled title="Coming soon">Exemptions</button>
                    </div>
                </div>
            </div>
            <div class="card-footer text-end">
                <a href="{{ route('categories.deduction.index') }}" class="btn btn-light me-2">Cancel</a>
                <button class="btn btn-primary" type="submit">Create</button>
            </div>
        </form>
    </div>
</div>
@push('scripts')
<script>
    ajaxCreate("{{ route('categories.deduction.index') }}");
</script>
@endpush
@endsection