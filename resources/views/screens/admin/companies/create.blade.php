@section('title', 'Create Company')
@extends('layouts.admin.master')
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card height-equal">
                <div class="card-header card-no-border">
                    <h2 class="form-heading">General Information</h2>
                </div>
                <div class="card-body basic-wizard important-validation">
                    <div id="msform">
                        <form class="ajax-form" action="{{ route('companies.store') }}" id="submit-form" method="POST">
                            @csrf
                            <div class="steps stepper-one row g-3 needs-validation">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Company Name</label>
                                        <input type="text" class="form-control" id="company_name" name="company_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="address_1">Address 1</label>
                                        <input type="text" class="form-control" id="address_1" name="address_1" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="address_2">Address 2</label>
                                        <input type="text" class="form-control" id="address_2" name="address_2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="contact_name">Contact Name</label>
                                        <input type="text" class="form-control" id="contact_name" name="contact_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="tel_number">Telephone Number</label>
                                        <input type="text" class="form-control" id="tel_number" name="tel_number" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="fax_number">Fax Number</label>
                                        <input type="text" class="form-control" id="fax_number" name="fax_number" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="email">E-mail Address</label>
                                        <input type="text" class="form-control" id="email" name="email" required>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-two row g-3 needs-validation">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="name">Company Type</label>
                                        <select class="form-select" id="company_type_id" name="company_type_id" required>
                                            @forelse ($companyTypes ?? [] as $type)
                                            <option value="{{ $type->id }}" @selected($type->title === 'Regular (Form 941)')>{{ $type->title }}</option>
                                            @empty
                                            <option value="" disabled selected>No company types available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="employer_identification_number">Employer Identification Number</label>
                                        <input type="text" class="form-control" id="employer_identification_number" name="employer_identification_number" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="trade_name">Trade Name (if any)</label>
                                        <input type="text" class="form-control" id="trade_name" name="trade_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <input
                                            name="round_federal_tax"
                                            class="form-check-input"
                                            id="flexCheckChecked"
                                            type="checkbox"
                                            value=""
                                            checked="" />
                                        <label
                                            class="form-check-label"
                                            for="flexCheckChecked">Round Federal Income Tax to the Nearest Dollar</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <h2 class="">W3 Information</h2>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="control_number">Control Number</label>
                                        <input type="text" class="form-control" id="control_number" name="control_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="establishment_number">Establishment Number</label>
                                        <input type="text" class="form-control" id="establishment_number" name="establishment_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="other_ein">Other EIN Used This Year</label>
                                        <input type="text" class="form-control" id="other_ein" name="other_ein" required>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-three row g-3 needs-validation">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_id">State ID</label>
                                        <input type="text" class="form-control" id="state_id" name="state_id">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_account_number">State Unemployment Account Number</label>
                                        <input type="text" class="form-control" id="state_unemp_account_number" name="state_unemp_account_number">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_tax_rate_q1">State Unemployment Tax Rate (1st Quarter)</label>
                                        <input type="number" class="form-control" id="state_unemp_tax_rate_q1" min="0" step="any" name="state_unemp_tax_rate_q1">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_tax_rate_q2">State Unemployment Tax Rate (2nd Quarter)</label>
                                        <input type="number" class="form-control" id="state_unemp_tax_rate_q2" min="0" step="any" name="state_unemp_tax_rate_q2">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_tax_rate_q3">State Unemployment Tax Rate (3rd Quarter)</label>
                                        <input type="number" class="form-control" id="state_unemp_tax_rate_q3" min="0" step="any" name="state_unemp_tax_rate_q3">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_tax_rate_q4">State Unemployment Tax Rate (4th Quarter)</label>
                                        <input type="number" class="form-control" id="state_unemp_tax_rate_q4" min="0" step="any" name="state_unemp_tax_rate_q4">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state_unemp_wage_base">State Unemployment Tax Wage Base</label>
                                        <input type="number" class="form-control" id="state_unemp_wage_base" min="0" step="any" name="state_unemp_wage_base">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="first_fiscal_month">First Fiscal Month</label>
                                    <select class="form-select" id="first_fiscal_month" name="first_fiscal_month" required>
                                        <option value="" disabled selected>January</option>
                                        <option value="2">February</option>
                                        <option value="3">March</option>
                                        <option value="4">April</option>
                                        <option value="5">May</option>
                                        <option value="6">June</option>
                                        <option value="7">July</option>
                                        <option value="8">August</option>
                                        <option value="9">September</option>
                                        <option value="10">October</option>
                                        <option value="11">November</option>
                                        <option value="12">December</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <input
                                            name="round_state_income_tax"
                                            class="form-check-input"
                                            id="round_state_income_tax"
                                            type="checkbox"
                                            value=""
                                            checked="" />
                                        <label
                                            class="form-check-label"
                                            for="round_state_income_tax">Round State Income Tax to the Nearest Dollar</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <input
                                            name="hide_ssn_on_paystub"
                                            class="form-check-input"
                                            id="hide_ssn_on_paystub"
                                            type="checkbox"
                                            value=""
                                            checked="" />
                                        <label
                                            class="form-check-label"
                                            for="hide_ssn_on_paystub">Hide Employees Social Security Numbers in Printed Paystubs and Direct Deposit Files</label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="wizard-footer d-flex gap-2 justify-content-end">
                        <button
                            type="button"
                            class="btn button-light-primary"
                            id="backbtn">
                            Back
                        </button>
                        <button
                            type="button"
                            class="btn btn-primary"
                            id="nextbtn">
                            Next
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
@push('scripts')
@include('includes.js.step-form')
<script>
    ajaxCreate("{{ route('companies.index') }}");
</script>
@endpush
@endsection