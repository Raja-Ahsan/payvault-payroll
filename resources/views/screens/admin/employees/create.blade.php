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
                            <div class="steps stepper-one row g-3 needs-validation">
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
                            </div>
                            <div class="steps stepper-two row g-3 needs-validation">
                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">Federal Income Tax Setup</legend>
                                    @php
                                        $useNewW4 = (bool) old('use_new_w4_2020');
                                    @endphp
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
                                                <select name="fed_allowances" class="form-control" id="fed_allowances" @disabled($useNewW4)>
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
                                                <input type="checkbox" name="use_new_w4_2020" class="form-check-input" id="use_new_w4_2020" value="1" @checked($useNewW4)>
                                                <label class="form-check-label" for="use_new_w4_2020">Use new W-4 (2020 &amp; beyond)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input type="checkbox" name="w4_step2_two_jobs" class="form-check-input" id="w4_step2_two_jobs" value="1" @disabled(!$useNewW4) @checked(old('w4_step2_two_jobs'))>
                                                <label class="form-check-label @if (!$useNewW4) text-muted @endif" for="w4_step2_two_jobs" id="w4_step2_two_jobs_label">Step 2 (2 Jobs)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step3_dependents">Step 3 (Dependents) $</label>
                                                <input type="number" name="w4_step3_dependents" class="form-control" id="w4_step3_dependents" min="0" step="0.01" value="{{ old('w4_step3_dependents', '0.00') }}" @disabled(!$useNewW4)>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4a_other_income">Step 4a (Other Income) $</label>
                                                <input type="number" name="w4_step4a_other_income" class="form-control" id="w4_step4a_other_income" min="0" step="0.01" value="{{ old('w4_step4a_other_income', '0.00') }}" @disabled(!$useNewW4)>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4b_deductions">Step 4b (Deductions) $</label>
                                                <input type="number" name="w4_step4b_deductions" class="form-control" id="w4_step4b_deductions" min="0" step="0.01" value="{{ old('w4_step4b_deductions', '0.00') }}" @disabled(!$useNewW4)>
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
                            </div>
                            <div class="steps stepper-three row g-3 needs-validation">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="justify-content-between mb-3">
                                            @foreach ($incomeCategoriesTypes as $incomeType)
                                            @foreach ($incomeType->categories as $category)
                                            <div class="form-group d-flex mb-3 gap-2 toggle-input-wrapper">
                                                <input type="checkbox"
                                                    name="income_category_id[]"
                                                    value="{{ $category->id }}"
                                                    class="form-check-input toggle-checkbox mt-0"
                                                    id="cat_{{ $category->id }}">

                                                <label class="form-check-label" for="cat_{{ $category->id }}">
                                                    {{ $category->title }}

                                                </label>
                                                <div class="d-flex  flex-grow-1 justify-content-end align-items-center gap-2">
                                                    ({{ $incomeType->title }})
                                                    <input type="number" min="0" step="0.01" class="form-control toggle-input" name="income_category_amount[]" disabled value="" style="max-width: 250px;">
                                                </div>
                                            </div>
                                            @endforeach
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="steps stepper-four row g-3 needs-validation">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="justify-content-between mb-3">
                                            @foreach ($taxCategories as $taxCategory)
                                            <div class="tax-category-wrapper mb-3">
                                                <input type="checkbox"
                                                name="income_category_id[]"
                                                value="{{$taxCategory->id}}"
                                                class="form-check-input toggle-checkbox mt-0"
                                                id="tax_category_{{ $taxCategory->id }}">
                                                <label class="form-check-label" for="tax_category_{{ $taxCategory->id }}">{{$taxCategory->title}}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-five row g-3 needs-validation">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="justify-content-between mb-3">
                                            @foreach ($deductionCategories as $deductionCategory)
                                            <div class="deduction-category-wrapper mb-3 d-flex">
                                                <input type="checkbox"
                                                    name="deduction_category_id[]"
                                                    value="{{$deductionCategory->id}}"
                                                    class="form-check-input toggle-checkbox mt-0"
                                                    id="deduction_category_{{ $deductionCategory->id }}">
                                                    <label for="deduction_category_{{ $deductionCategory->id }}">{{$deductionCategory->title}}</label>
                                                    <div class="d-flex  flex-grow-1 justify-content-end align-items-center gap-2">
                                                        <label for="deduction_category_{{ $deductionCategory->id }}">{{$deductionCategory->incomeType->title}}</label>
                                                        <input type="number" min="0" step="0.01" class="form-control toggle-input" name="deduction_category_amount[]" disabled value="" style="max-width: 250px;">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-six row g-3 needs-validation">
                                <div class="toggle-input-wrapper">
                                    <input type="checkbox"
                                        name="include_in_direct_deposit"
                                        value="1"
                                        class="form-check-input toggle-checkbox mt-0"
                                        id="include_in_direct_deposit">
                                    <label for="include_in_direct_deposit">Include In Direct Deposit Process</label>

                                    <fieldset disabled class="toggle-input border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                        <legend class="float-none w-auto px-2 mb-2 fs-6">Banking Information</legend>
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="account_type">Account Type</label>
                                                <select name="account_type" class="form-control" id="account_type">
                                                    <option value="checking">Checking</option>
                                                    <option value="savings">Savings</option>
                                                </select>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="bank_routing_number">Bank Routing Number</label>
                                                <input type="text"
                                                    name="bank_routing_number"
                                                    id="bank_routing_number"
                                                    class="form-control"
                                                    inputmode="numeric"
                                                    autocomplete="off"
                                                    minlength="9"
                                                    maxlength="9"
                                                    pattern="[0-9]{9}"
                                                    title="Enter all 9 digits of the routing number"
                                                    data-minlength-message="Routing number must be 9 digits"
                                                    required
                                                    oninput="this.value = this.value.replace(/\D/g, '').slice(0, 9)" required>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="account_number">Account Number</label>
                                                <input type="text"
                                                    name="account_number"
                                                    class="form-control"
                                                    minlength="23"
                                                    maxlength="23"
                                                    inputmode="numeric"
                                                    pattern="[0-9]{23}"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                            </div>
                                        </div>
                                    </fieldset>
                                </div>
                            </div>
                            <div class="steps stepper-seven row g-3 needs-validation">
                                <div class="row col-12">
                                    <div class="col-12 mb-4">
                                        <span class="d-block fw-medium mb-2">Method of Calculating Vacation and Sick Hours:</span>
                                        <div class="form-check">
                                            <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_check" value="per_check" checked>
                                            <label class="form-check-label" for="vacation_sick_method_per_check">Per Check</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_total_hours" value="per_total_hours">
                                            <label class="form-check-label" for="vacation_sick_method_per_total_hours">Per Total Hours on Check</label>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3 d-flex flex-wrap align-items-center gap-3">
                                        <label class="mb-0 flex-shrink-0" for="vacation_hours_earned_per_unit" style="min-width: 280px;">
                                            Vacation Hours Earned <span id="js-vacation-earned-method-label">Per Check</span>
                                        </label>
                                        <input type="number"
                                            name="vacation_hours_earned_per_unit"
                                            id="vacation_hours_earned_per_unit"
                                            class="form-control"
                                            min="0"
                                            step="0.01"
                                            value="0.00"
                                            style="max-width: 140px;">
                                    </div>

                                    <div class="col-12 mb-3 d-flex flex-wrap align-items-start gap-3">
                                        <label class="mb-0 flex-shrink-0 pt-1" for="max_vacation_hours_per_year" style="min-width: 280px;">Maximum Vacation Hours Earned Per Year</label>
                                        <div class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                                            <input type="number"
                                                name="max_vacation_hours_per_year"
                                                id="max_vacation_hours_per_year"
                                                class="form-control"
                                                min="0"
                                                step="0.01"
                                                value=""
                                                placeholder="0.00"
                                                style="max-width: 140px;">
                                            <small class="text-info mb-0 text-muted" style="max-width: 420px;">Keep blank for unlimited hours per year; fill with 0.00 for zero hours per year</small>
                                        </div>
                                    </div>

                                    <div class="col-12 mb-3 d-flex flex-wrap align-items-center gap-3">
                                        <label class="mb-0 flex-shrink-0" for="sick_hours_earned_per_unit" style="min-width: 280px;">
                                            Sick Hours Earned <span id="js-sick-earned-method-label">Per Check</span>
                                        </label>
                                        <input type="number"
                                            name="sick_hours_earned_per_unit"
                                            id="sick_hours_earned_per_unit"
                                            class="form-control"
                                            min="0"
                                            step="0.01"
                                            value="0.00"
                                            style="max-width: 140px;">
                                    </div>

                                    <div class="col-12 mb-3 d-flex flex-wrap align-items-start gap-3">
                                        <label class="mb-0 flex-shrink-0 pt-1" for="max_sick_hours_per_year" style="min-width: 280px;">Maximum Sick Hours Earned Per Year</label>
                                        <div class="d-flex flex-wrap align-items-center gap-3 flex-grow-1">
                                            <input type="number"
                                                name="max_sick_hours_per_year"
                                                id="max_sick_hours_per_year"
                                                class="form-control"
                                                min="0"
                                                step="0.01"
                                                value=""
                                                placeholder="0.00"
                                                style="max-width: 140px;">
                                            <small class="text-info mb-0 text-muted" style="max-width: 420px;">Keep blank for unlimited hours per year; fill with 0.00 for zero hours per year</small>
                                        </div>
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

<!-- <x-modals.modal id="incomeModal" title="Confirm Action" size="modal-lg">
    
    <p>Do you want to continue?</p>

    <x-slot name="footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        <button class="btn btn-primary" id="confirmYes">Yes</button>
    </x-slot>

</x-modal> -->
@push('scripts')
@include('includes.js.step-form')
<script>
    ajaxCreate("{{ route('companies.index') }}");

    const toggleInputWrapper = document.querySelectorAll('.toggle-input-wrapper');
    const toggleCheckbox = document.querySelectorAll('.toggle-checkbox');
    const toggleInput = document.querySelectorAll('.toggle-input');

    toggleCheckbox.forEach((checkbox, index) => {
        checkbox.addEventListener('change', (e) => {
            if (e.target.checked) {
                toggleInput[index].disabled = false;
            } else {
                toggleInput[index].disabled = true;
            }
        });
    });

    (function () {
        const labels = {
            per_check: 'Per Check',
            per_total_hours: 'Per Total Hours on Check',
        };
        const vacationEl = document.getElementById('js-vacation-earned-method-label');
        const sickEl = document.getElementById('js-sick-earned-method-label');
        const radios = document.querySelectorAll('input[name="vacation_sick_calculation_method"]');

        function syncVacationSickMethodLabels() {
            const selected = document.querySelector('input[name="vacation_sick_calculation_method"]:checked');
            const key = selected ? selected.value : 'per_check';
            const text = labels[key] || labels.per_check;
            if (vacationEl) {
                vacationEl.textContent = text;
            }
            if (sickEl) {
                sickEl.textContent = text;
            }
        }

        radios.forEach((radio) => {
            radio.addEventListener('change', syncVacationSickMethodLabels);
        });
        syncVacationSickMethodLabels();
    })();

    (function () {
        const useNewW4 = document.getElementById('use_new_w4_2020');
        const fedAllowances = document.getElementById('fed_allowances');
        const step2 = document.getElementById('w4_step2_two_jobs');
        const step3 = document.getElementById('w4_step3_dependents');
        const step4a = document.getElementById('w4_step4a_other_income');
        const step4b = document.getElementById('w4_step4b_deductions');
        const step2Label = document.getElementById('w4_step2_two_jobs_label');

        function syncNewW4Mode() {
            if (!useNewW4) {
                return;
            }
            const on = useNewW4.checked;
            if (fedAllowances) {
                fedAllowances.disabled = on;
            }
            [step2, step3, step4a, step4b].forEach(function (el) {
                if (el) {
                    el.disabled = !on;
                }
            });
            if (step2Label) {
                step2Label.classList.toggle('text-muted', !on);
            }
        }

        if (useNewW4) {
            useNewW4.addEventListener('change', syncNewW4Mode);
            syncNewW4Mode();
        }
    })();

    // const bankRoutingNumber = document.getElementById('bank_routing_number');

    // bankRoutingNumber.addEventListener('input', (e) => {
    //     e.target.type = 'number';
    //     e.target.value = e.target.value.replace(/[^0-9]/g, '');
    //     if (e.target.value.length === 9) {
    //         e.target.removeAttribute('required');
    //     }
    // });
</script>
@endpush
@endsection