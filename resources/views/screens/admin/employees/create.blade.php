@section('title', 'New Employee')
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
                        <form class="ajax-form" action="{{ route('employees.store') }}" id="submit-form" method="POST">
                            @csrf
                            <!-- <div class="steps stepper-one row g-3 needs-validation">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" required>
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
                                        <select name="state_id" class="form-control" id="state_id">
                                            @forelse ($states ?? [] as $state)
                                            <option value="{{ $state->id }}" @selected($state->name === 'California')>{{ $state->name }}</option>
                                            @empty
                                            <option value="" disabled selected>No states available</option>
                                            @endforelse
                                        </select>
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
                                        <label for="ssn">Social Security Number</label>
                                        <input type="text" class="form-control" id="ssn" name="ssn" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="dob">Date Of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="fax">Fax</label>
                                        <input type="text" class="form-control" id="fax" name="fax">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="employee_id">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <input
                                            name="inactive"
                                            class="form-check-input"
                                            id="inactive"
                                            type="checkbox"
                                            value=""
                                            checked="" />
                                        <label
                                            class="form-check-label"
                                            for="inactive">inactive</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="message">Memo</label>
                                        <textarea name="message" class="form-control text-area" id="message"></textarea>
                                    </div>
                                </div>
                            </div> -->
                            <div class="steps stepper-two row g-3 needs-validation">
                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">Federal Income Tax Setup</legend>
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="fed_filing_status">Filing Status</label>
                                                <select name="fed_filing_status" class="form-control" id="fed_filing_status">
                                                    <option value="single" selected>Single</option>
                                                    <option value="married_filing_jointly">Married Filing Jointly</option>
                                                    <option value="married_filing_separately">Married Filing Separately</option>
                                                    <option value="head_of_household">Head of Household</option>
                                                    <option value="qualifying_surviving_spouse">Qualifying Surviving Spouse</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="fed_allowances"># Of Allowances</label>
                                                <select name="fed_allowances" class="form-control" id="fed_allowances">
                                                    @for ($i = 0; $i <= 99; $i++)
                                                        <option value="{{ $i }}" @selected(old('fed_allowances', '0' )==(string) $i)>{{ $i }}</option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="pay_frequency">Pay Frequency</label>
                                                <select name="pay_frequency" class="form-control" id="pay_frequency">
                                                    <option value="daily_260">Daily (260 pay periods)</option>
                                                    <option value="weekly_52" selected>Weekly (52 Pay Periods)</option>
                                                    <option value="biweekly_26">Bi-Weekly (26 Pay Periods)</option>
                                                    <option value="semimonthly_24">Semi-Monthly (24 Pay Periods)</option>
                                                    <option value="monthly_12">Monthly (12 Pay Periods)</option>
                                                    <option value="quarterly_4">Quarterly (4 pay periods)</option>
                                                    <option value="annual_1">Annual (1 pay period)</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="additional_fed_withholding">Additional Fed. Withholding</label>
                                                <input type="number" name="additional_fed_withholding" class="form-control" id="additional_fed_withholding" min="0" step="0.01" value="{{ old('additional_fed_withholding', '0.00') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="checkbox" name="use_new_w4_2020" class="form-check-input" id="use_new_w4_2020" value="1">
                                                <label class="form-check-label" for="use_new_w4_2020">Use new W-4 (2020 &amp; beyond)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input type="checkbox" name="w4_step2_two_jobs" class="form-check-input" id="w4_step2_two_jobs" value="1" disabled>
                                                <label class="form-check-label text-muted" for="w4_step2_two_jobs">Step 2 (2 Jobs)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step3_dependents">Step 3 (Dependents) $</label>
                                                <input type="number" name="w4_step3_dependents" class="form-control" id="w4_step3_dependents" min="0" step="0.01" value="{{ old('w4_step3_dependents', '0.00') }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4a_other_income">Step 4a (Other Income) $</label>
                                                <input type="number" name="w4_step4a_other_income" class="form-control" id="w4_step4a_other_income" min="0" step="0.01" value="{{ old('w4_step4a_other_income', '0.00') }}">
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4b_deductions">Step 4b (Deductions) $</label>
                                                <input type="number" name="w4_step4b_deductions" class="form-control" id="w4_step4b_deductions" min="0" step="0.01" value="{{ old('w4_step4b_deductions', '0.00') }}">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">W-2 Options</legend>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_statutory_employee" class="form-check-input" id="w2_statutory_employee" value="1">
                                            <label class="form-check-label" for="w2_statutory_employee">Statutory Employee</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_retirement_plan" class="form-check-input" id="w2_retirement_plan" value="1">
                                            <label class="form-check-label" for="w2_retirement_plan">Retirement Plan</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_advance_eic" class="form-check-input" id="w2_advance_eic" value="1">
                                            <label class="form-check-label" for="w2_advance_eic">This employee receives Advance EIC payment</label>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">Set the following Taxes to zero on the check</legend>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_federal_income" class="form-check-input" id="tax_zero_federal_income" value="1">
                                            <label class="form-check-label" for="tax_zero_federal_income">Federal Income</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_state_income" class="form-check-input" id="tax_zero_state_income" value="1">
                                            <label class="form-check-label" for="tax_zero_state_income">State Income</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_ss_med_employee" class="form-check-input" id="tax_zero_ss_med_employee" value="1">
                                            <label class="form-check-label" for="tax_zero_ss_med_employee">SS. &amp; Med. (Employee)</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_ss_med_employer" class="form-check-input" id="tax_zero_ss_med_employer" value="1">
                                            <label class="form-check-label" for="tax_zero_ss_med_employer">SS. &amp; Med. (Employer)</label>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">State Income Tax Setup</legend>
                                    <div class="row">
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="withholding_state_id">State</label>
                                                <!-- <select name="withholding_state_id" class="form-control" id="withholding_state_id">
                                                    @forelse ($states ?? [] as $state)
                                                    <option value="{{ $state->id }}" @selected(old('withholding_state_id')==$state->id || (old('withholding_state_id') === null && ($state->code ?? '') === 'GA'))>{{ $state->code }} — {{ $state->name }}</option>
                                                    @empty
                                                    <option value="" disabled selected>No states available</option>
                                                    @endforelse
                                                </select> -->
                                                <select name="withholding_state_id" class="form-control" id="withholding_state_id">
                                                    @forelse ($states ?? [] as $state)
                                                    <option value="{{ $state->id }}" @selected($state->name === 'California')>{{ $state->name }}</option>
                                                    @empty
                                                    <option value="" disabled selected>No states available</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="additional_state_withholding">Additional State Withholding</label>
                                                <input type="number" name="additional_state_withholding" class="form-control" id="additional_state_withholding" min="0" step="0.01" value="{{ old('additional_state_withholding', '0.00') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_filing_status">Filing Status</label>
                                                <select name="state_filing_status" class="form-control" id="state_filing_status">
                                                    <option value="single" selected>Single</option>
                                                    <option value="married_joint_two_incomes">Married Joint Two Incomes</option>
                                                    <option value="married_joint_one_income">Married Joint One Income</option>
                                                    <option value="married_filing_separately">Married Filing Separately</option>
                                                    <option value="head_of_household">Head of Household</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_personal_allowances">Personal Allowances</label>
                                                <input type="number" name="state_personal_allowances" class="form-control" id="state_personal_allowances" min="0" step="1" value="{{ old('state_personal_allowances', '0') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_dependent_allowances">Dependent Allowances</label>
                                                <input type="number" name="state_dependent_allowances" class="form-control" id="state_dependent_allowances" min="0" step="1" value="{{ old('state_dependent_allowances', '0') }}">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <input name="inactive" class="form-check-input" id="inactive" type="checkbox" value="1">
                                        <label class="form-check-label" for="inactive">Inactive</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="message">Memo</label>
                                        <textarea name="message" class="form-control text-area" id="message"></textarea>
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