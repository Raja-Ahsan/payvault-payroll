@section('title', 'Edit Company')
@extends('layouts.admin.master')
@php
$address = $company->address;
$federal = $company->federalTaxInformation;
$state = $company->stateTaxInformation;
@endphp
@section('content')
<div class="container-fluid user-list-wrapper">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-no-border text-end">
                    {{-- header actions --}}
                </div>
                <div class="card-body">
                    <ul class="nav nav-pills mb-3 custom-tab-wrapper" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-general-tab" data-bs-toggle="pill" data-bs-target="#pills-general" type="button" role="tab" aria-controls="pills-general" aria-selected="true">General Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-federal-tab" data-bs-toggle="pill" data-bs-target="#pills-federal" type="button" role="tab" aria-controls="pills-federal" aria-selected="false">Federal Tax Information</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-state-tab" data-bs-toggle="pill" data-bs-target="#pills-state" type="button" role="tab" aria-controls="pills-state" aria-selected="false">State Tax Informtaion</button>
                        </li>
                        <!-- <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-income-tab" data-bs-toggle="pill" data-bs-target="#pills-income" type="button" role="tab" aria-controls="pills-income" aria-selected="false">Income Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-tax-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-tax-categories" type="button" role="tab" aria-controls="pills-tax-categories" aria-selected="false">Income Categories</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-deduction-categories-tab" data-bs-toggle="pill" data-bs-target="#pills-deduction-categories" type="button" role="tab" aria-controls="pills-deduction-categories" aria-selected="false">Deduction Categories</button>
                        </li> -->
                    </ul>
                    <form action="{{ route('companies.update', $company) }}" id="company-edit-form" method="POST">
                        @csrf
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade show active" id="pills-general" role="tabpanel" aria-labelledby="pills-general-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="company_name">Company Name</label>
                                            <input type="text" class="form-control" id="company_name" name="company_name" value="{{ old('company_name', $company->company_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="address_1">Address 1</label>
                                            <input type="text" class="form-control" id="address_1" name="address_1" value="{{ old('address_1', $address->address_1 ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="address_2">Address 2</label>
                                            <input type="text" class="form-control" id="address_2" name="address_2" value="{{ old('address_2', $address->address_2 ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $address->city ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="address_state_id">State</label>
                                            <select name="address_state_id" id="address_state_id" class="form-control" required>
                                                <option value="" disabled @selected(! $addressStateId)>Select state</option>
                                                @foreach ($states ?? [] as $st)
                                                    <option value="{{ $st->id }}" @selected((string) ($addressStateId ?? '') === (string) $st->id)>{{ $st->name }}</option>
                                                @endforeach
                                            </select>
                                            @if (($states ?? collect())->isEmpty())
                                                <small class="text-danger d-block mt-1">No states in database. Run states seeder.</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="zip_code">Zip Code</label>
                                            <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $address->zip_code ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="contact_name">Contact Name</label>
                                            <input type="text" class="form-control" id="contact_name" name="contact_name" value="{{ old('contact_name', $company->contact_name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="tel_number">Telephone Number</label>
                                            <input type="text" class="form-control" id="tel_number" name="tel_number" value="{{ old('tel_number', $company->tel_number) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="fax_number">Fax Number</label>
                                            <input type="text" class="form-control" id="fax_number" name="fax_number" value="{{ old('fax_number', $company->fax_number) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="email">E-mail Address</label>
                                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $company->email) }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-federal" role="tabpanel" aria-labelledby="pills-federal-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="company_type_id">Company Type</label>
                                            <select class="form-select" id="company_type_id" name="company_type_id" required>
                                                @forelse ($companyTypes ?? [] as $type)
                                                <option value="{{ $type->id }}" @selected(old('company_type_id', $federal->company_type_id ?? '') == $type->id)>{{ $type->title }}</option>
                                                @empty
                                                <option value="" disabled>No company types available</option>
                                                @endforelse
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="employer_identification_number">Employer Identification Number</label>
                                            <input type="text" class="form-control" id="employer_identification_number" name="employer_identification_number" value="{{ old('employer_identification_number', $federal->employer_identification_number ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="trade_name">Trade Name (if any)</label>
                                            <input type="text" class="form-control" id="trade_name" name="trade_name" value="{{ old('trade_name', $federal->trade_name ?? '') }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <input
                                                name="round_federal_tax"
                                                class="form-check-input"
                                                id="round_federal_tax"
                                                type="checkbox"
                                                value="1"
                                                @checked(old('round_federal_tax', $federal->round_federal_tax ?? false)) />
                                            <label class="form-check-label" for="round_federal_tax">Round Federal Income Tax to the Nearest Dollar</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <h2 class="h5 mb-3">W3 Information</h2>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="control_number">Control Number</label>
                                            <input type="text" class="form-control" id="control_number" name="control_number" value="{{ old('control_number', $federal->control_number ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="establishment_number">Establishment Number</label>
                                            <input type="text" class="form-control" id="establishment_number" name="establishment_number" value="{{ old('establishment_number', $federal->establishment_number ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="other_ein">Other EIN Used This Year</label>
                                            <input type="text" class="form-control" id="other_ein" name="other_ein" value="{{ old('other_ein', $federal->other_ein ?? '') }}" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-state" role="tabpanel" aria-labelledby="pills-state-tab" tabindex="0">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_id">State ID</label>
                                            <input type="text" class="form-control" id="state_id" name="state_id" value="{{ old('state_id', $state->state_id ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_account_number">State Unemployment Account Number</label>
                                            <input type="text" class="form-control" id="state_unemp_account_number" name="state_unemp_account_number" value="{{ old('state_unemp_account_number', $state->state_unemp_account_number ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_tax_rate_q1">State Unemployment Tax Rate (1st Quarter)</label>
                                            <input type="number" class="form-control" id="state_unemp_tax_rate_q1" min="0" step="any" name="state_unemp_tax_rate_q1" value="{{ old('state_unemp_tax_rate_q1', $state->state_unemp_tax_rate_q1 ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_tax_rate_q2">State Unemployment Tax Rate (2nd Quarter)</label>
                                            <input type="number" class="form-control" id="state_unemp_tax_rate_q2" min="0" step="any" name="state_unemp_tax_rate_q2" value="{{ old('state_unemp_tax_rate_q2', $state->state_unemp_tax_rate_q2 ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_tax_rate_q3">State Unemployment Tax Rate (3rd Quarter)</label>
                                            <input type="number" class="form-control" id="state_unemp_tax_rate_q3" min="0" step="any" name="state_unemp_tax_rate_q3" value="{{ old('state_unemp_tax_rate_q3', $state->state_unemp_tax_rate_q3 ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_tax_rate_q4">State Unemployment Tax Rate (4th Quarter)</label>
                                            <input type="number" class="form-control" id="state_unemp_tax_rate_q4" min="0" step="any" name="state_unemp_tax_rate_q4" value="{{ old('state_unemp_tax_rate_q4', $state->state_unemp_tax_rate_q4 ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group mb-3">
                                            <label for="state_unemp_wage_base">State Unemployment Tax Wage Base</label>
                                            <input type="number" class="form-control" id="state_unemp_wage_base" min="0" step="any" name="state_unemp_wage_base" value="{{ old('state_unemp_wage_base', $state->state_unemp_wage_base ?? '') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="first_fiscal_month" class="d-block">First Fiscal Month</label>
                                        <select class="form-select" id="first_fiscal_month" name="first_fiscal_month" required>
                                            @foreach (range(1, 12) as $m)
                                            @php
                                            $monthLabel = date('F', mktime(0, 0, 0, $m, 1));
                                            @endphp
                                            <option value="{{ $m }}" @selected(old('first_fiscal_month', $state->first_fiscal_month ?? '1') == (string) $m)>{{ $monthLabel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <input
                                                name="round_state_income_tax"
                                                class="form-check-input"
                                                id="round_state_income_tax"
                                                type="checkbox"
                                                value="1"
                                                @checked(old('round_state_income_tax', $state->round_state_income_tax ?? false)) />
                                            <label class="form-check-label" for="round_state_income_tax">Round State Income Tax to the Nearest Dollar</label>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group mb-3">
                                            <input
                                                name="hide_ssn_on_paystub"
                                                class="form-check-input"
                                                id="hide_ssn_on_paystub"
                                                type="checkbox"
                                                value="1"
                                                @checked(old('hide_ssn_on_paystub', $state->hide_ssn_on_paystub ?? false)) />
                                            <label class="form-check-label" for="hide_ssn_on_paystub">Hide Employees Social Security Numbers in Printed Paystubs and Direct Deposit Files</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pills-income" role="tabpanel" aria-labelledby="pills-income-tab" tabindex="0">...</div>
                            <div class="tab-pane fade" id="pills-tax-categories" role="tabpanel" aria-labelledby="pills-tax-categories-tab" tabindex="0">...</div>
                            <div class="tab-pane fade" id="pills-deduction-categories" role="tabpanel" aria-labelledby="pills-deduction-categories-tab" tabindex="0">...</div>
                        </div>
                        <div class="col-md-12 d-flex justify-content-end">
                            <div class="form-group mb-3">
                                <button class="btn btn-primary" type="submit">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
<script>
    ajaxUpdate('#company-edit-form', "{{ route('companies.index') }}");
</script>
@endpush
@endsection