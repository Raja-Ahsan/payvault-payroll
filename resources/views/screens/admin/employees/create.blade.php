@section('title', 'New Employee')
@extends('layouts.admin.master')
@section('content')
@php
    $detail = $employee?->detail;
@endphp
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
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="company_id">Company <span class="text-danger">*</span></label>
                                        <select name="company_id" id="company_id" class="form-control" required>
                                            <option value="" disabled @selected(! old('company_id'))>Select company</option>
                                            @foreach ($companies ?? [] as $company)
                                                <option value="{{ $company->id }}" @selected((string) old('company_id', $employee?->company_id) === (string) $company->id)>{{ $company->company_name }}</option>
                                            @endforeach
                                        </select>
                                        @if (($companies ?? collect())->isEmpty())
                                            <small class="text-danger d-block mt-1">No companies available. Create a company first.</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="first_name">First Name</label>
                                        <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name', $employee?->first_name) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="middle_name">Middle Name</label>
                                        <input type="text" class="form-control" id="middle_name" name="middle_name" value="{{ old('middle_name', $employee?->middle_name) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="last_name">Last Name</label>
                                        <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name', $employee?->last_name) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="address_1">Address 1</label>
                                        <input type="text" class="form-control" id="address_1" name="address_1" value="{{ old('address_1', $employee?->address_1) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="address_2">Address 2</label>
                                        <input type="text" class="form-control" id="address_2" name="address_2" value="{{ old('address_2', $employee?->address_2) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city', $employee?->city) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="state">State</label>
                                        <select name="state_id" class="form-control" id="state_id">
                                            @forelse ($states ?? [] as $state)
                                            <option value="{{ $state->id }}" @selected((string) old('state_id', $employee?->state_id ?? optional($states->firstWhere('name', 'California'))->id) === (string) $state->id)>{{ $state->name }}</option>
                                            @empty
                                            <option value="" disabled selected>No states available</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" class="form-control" id="zip_code" name="zip_code" value="{{ old('zip_code', $employee?->zip_code) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="ssn">Social Security Number</label>
                                        <input type="text" class="form-control" id="ssn" name="ssn" value="{{ old('ssn', $employee?->ssn) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="dob">Date Of Birth</label>
                                        <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', optional($employee?->dob)->format('Y-m-d')) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="phone">Phone</label>
                                        <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $employee?->phone) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="fax">Fax</label>
                                        <input type="text" class="form-control" id="fax" name="fax" value="{{ old('fax', $employee?->fax) }}">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $employee?->email) }}" required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="employee_id">Employee ID</label>
                                        <input type="text" class="form-control" id="employee_id" name="employee_id" value="{{ old('employee_id', $employee?->employee_id) }}">
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <input type="hidden" name="inactive" value="0">
                                        <input
                                            name="inactive"
                                            class="form-check-input"
                                            id="inactive"
                                            type="checkbox"
                                            value="1"
                                            @checked(old('inactive', ($employee?->inactive ?? true) ? '1' : '0') === '1') />
                                        <label
                                            class="form-check-label"
                                            for="inactive">inactive</label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-3">
                                        <label for="message">Memo</label>
                                        <textarea name="message" class="form-control text-area" id="message">{{ old('message', $employee?->message) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-two row g-3 needs-validation">
                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">Federal Income Tax Setup</legend>
                                    @php
                                    $useNewW4 = (bool) old('use_new_w4_2020', $detail?->use_new_w4_2020);
                                    @endphp
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <label for="fed_filing_status">Filing Status</label>
                                                <select name="fed_filing_status" class="form-control" id="fed_filing_status">
                                                    <option value="single" @selected(old('fed_filing_status', $detail?->fed_filing_status ?? 'single') === 'single')>Single</option>
                                                    <option value="married_filing_jointly" @selected(old('fed_filing_status', $detail?->fed_filing_status ?? 'single') === 'married_filing_jointly')>Married Filing Jointly</option>
                                                    <option value="married_filing_separately" @selected(old('fed_filing_status', $detail?->fed_filing_status ?? 'single') === 'married_filing_separately')>Married Filing Separately</option>
                                                    <option value="head_of_household" @selected(old('fed_filing_status', $detail?->fed_filing_status ?? 'single') === 'head_of_household')>Head of Household</option>
                                                    <option value="qualifying_surviving_spouse" @selected(old('fed_filing_status', $detail?->fed_filing_status ?? 'single') === 'qualifying_surviving_spouse')>Qualifying Surviving Spouse</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="fed_allowances"># Of Allowances</label>
                                                <select name="fed_allowances" class="form-control" id="fed_allowances" @disabled($useNewW4)>
                                                    @for ($i = 0; $i <= 99; $i++)
                                                        <option value="{{ $i }}" @selected(old('fed_allowances', (string) ($detail?->fed_allowances ?? 0)) === (string) $i)>{{ $i }}</option>
                                                        @endfor
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="pay_frequency">Pay Frequency</label>
                                                <select name="pay_frequency" class="form-control" id="pay_frequency">
                                                    <option value="daily_260" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'daily_260')>Daily (260 pay periods)</option>
                                                    <option value="weekly_52" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'weekly_52')>Weekly (52 Pay Periods)</option>
                                                    <option value="biweekly_26" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'biweekly_26')>Bi-Weekly (26 Pay Periods)</option>
                                                    <option value="semimonthly_24" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'semimonthly_24')>Semi-Monthly (24 Pay Periods)</option>
                                                    <option value="monthly_12" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'monthly_12')>Monthly (12 Pay Periods)</option>
                                                    <option value="quarterly_4" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'quarterly_4')>Quarterly (4 pay periods)</option>
                                                    <option value="annual_1" @selected(old('pay_frequency', $detail?->pay_frequency ?? 'weekly_52') === 'annual_1')>Annual (1 pay period)</option>
                                                </select>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="additional_fed_withholding">Additional Fed. Withholding</label>
                                                <input type="number" name="additional_fed_withholding" class="form-control" id="additional_fed_withholding" min="0" step="0.01" value="{{ old('additional_fed_withholding', $detail?->additional_fed_withholding !== null ? number_format((float) $detail->additional_fed_withholding, 2, '.', '') : '0.00') }}">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group mb-3">
                                                <input type="checkbox" name="use_new_w4_2020" class="form-check-input" id="use_new_w4_2020" value="1" @checked($useNewW4)>
                                                <label class="form-check-label" for="use_new_w4_2020">Use new W-4 (2020 &amp; beyond)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <input type="checkbox" name="w4_step2_two_jobs" class="form-check-input" id="w4_step2_two_jobs" value="1" @disabled(!$useNewW4) @checked(old('w4_step2_two_jobs', $detail?->w4_step2_two_jobs))>
                                                <label class="form-check-label @if (!$useNewW4) text-muted @endif" for="w4_step2_two_jobs" id="w4_step2_two_jobs_label">Step 2 (2 Jobs)</label>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step3_dependents">Step 3 (Dependents) $</label>
                                                <input type="number" name="w4_step3_dependents" class="form-control" id="w4_step3_dependents" min="0" step="0.01" value="{{ old('w4_step3_dependents', $detail?->w4_step3_dependents !== null ? number_format((float) $detail->w4_step3_dependents, 2, '.', '') : '0.00') }}" @disabled(!$useNewW4)>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4a_other_income">Step 4a (Other Income) $</label>
                                                <input type="number" name="w4_step4a_other_income" class="form-control" id="w4_step4a_other_income" min="0" step="0.01" value="{{ old('w4_step4a_other_income', $detail?->w4_step4a_other_income !== null ? number_format((float) $detail->w4_step4a_other_income, 2, '.', '') : '0.00') }}" @disabled(!$useNewW4)>
                                            </div>
                                            <div class="form-group mb-3">
                                                <label for="w4_step4b_deductions">Step 4b (Deductions) $</label>
                                                <input type="number" name="w4_step4b_deductions" class="form-control" id="w4_step4b_deductions" min="0" step="0.01" value="{{ old('w4_step4b_deductions', $detail?->w4_step4b_deductions !== null ? number_format((float) $detail->w4_step4b_deductions, 2, '.', '') : '0.00') }}" @disabled(!$useNewW4)>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">W-2 Options</legend>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_statutory_employee" class="form-check-input" id="w2_statutory_employee" value="1" @checked(old('w2_statutory_employee', $detail?->w2_statutory_employee))>
                                            <label class="form-check-label" for="w2_statutory_employee">Statutory Employee</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_retirement_plan" class="form-check-input" id="w2_retirement_plan" value="1" @checked(old('w2_retirement_plan', $detail?->w2_retirement_plan))>
                                            <label class="form-check-label" for="w2_retirement_plan">Retirement Plan</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="w2_advance_eic" class="form-check-input" id="w2_advance_eic" value="1" @checked(old('w2_advance_eic', $detail?->w2_advance_eic))>
                                            <label class="form-check-label" for="w2_advance_eic">This employee receives Advance EIC payment</label>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                    <legend class="float-none w-auto px-2 mb-2 fs-6">Set the following Taxes to zero on the check</legend>
                                    <div class="row g-3">
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_federal_income" class="form-check-input" id="tax_zero_federal_income" value="1" @checked(old('tax_zero_federal_income', $detail?->tax_zero_federal_income))>
                                            <label class="form-check-label" for="tax_zero_federal_income">Federal Income</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_state_income" class="form-check-input" id="tax_zero_state_income" value="1" @checked(old('tax_zero_state_income', $detail?->tax_zero_state_income))>
                                            <label class="form-check-label" for="tax_zero_state_income">State Income</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_ss_med_employee" class="form-check-input" id="tax_zero_ss_med_employee" value="1" @checked(old('tax_zero_ss_med_employee', $detail?->tax_zero_ss_med_employee))>
                                            <label class="form-check-label" for="tax_zero_ss_med_employee">SS. &amp; Med. (Employee)</label>
                                        </div>
                                        <div class="col-auto">
                                            <input type="checkbox" name="tax_zero_ss_med_employer" class="form-check-input" id="tax_zero_ss_med_employer" value="1" @checked(old('tax_zero_ss_med_employer', $detail?->tax_zero_ss_med_employer))>
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
                                                    <option value="{{ $state->id }}" @selected((string) old('withholding_state_id', $detail?->withholding_state_id ?? optional($states->firstWhere('name', 'California'))->id) === (string) $state->id)>{{ $state->name }}</option>
                                                    @empty
                                                    <option value="" disabled selected>No states available</option>
                                                    @endforelse
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="additional_state_withholding">Additional State Withholding</label>
                                                <input type="number" name="additional_state_withholding" class="form-control" id="additional_state_withholding" min="0" step="0.01" value="{{ old('additional_state_withholding', $detail?->additional_state_withholding !== null ? number_format((float) $detail->additional_state_withholding, 2, '.', '') : '0.00') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_filing_status">Filing Status</label>
                                                <select name="state_filing_status" class="form-control" id="state_filing_status">
                                                    <option value="single" @selected(old('state_filing_status', $detail?->state_filing_status ?? 'single') === 'single')>Single</option>
                                                    <option value="married_joint_two_incomes" @selected(old('state_filing_status', $detail?->state_filing_status ?? 'single') === 'married_joint_two_incomes')>Married Joint Two Incomes</option>
                                                    <option value="married_joint_one_income" @selected(old('state_filing_status', $detail?->state_filing_status ?? 'single') === 'married_joint_one_income')>Married Joint One Income</option>
                                                    <option value="married_filing_separately" @selected(old('state_filing_status', $detail?->state_filing_status ?? 'single') === 'married_filing_separately')>Married Filing Separately</option>
                                                    <option value="head_of_household" @selected(old('state_filing_status', $detail?->state_filing_status ?? 'single') === 'head_of_household')>Head of Household</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_personal_allowances">Personal Allowances</label>
                                                <input type="number" name="state_personal_allowances" class="form-control" id="state_personal_allowances" min="0" step="1" value="{{ old('state_personal_allowances', $detail?->state_personal_allowances ?? '0') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-lg-4">
                                            <div class="form-group mb-3">
                                                <label for="state_dependent_allowances">Dependent Allowances</label>
                                                <input type="number" name="state_dependent_allowances" class="form-control" id="state_dependent_allowances" min="0" step="1" value="{{ old('state_dependent_allowances', $detail?->state_dependent_allowances ?? '0') }}">
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <div class="steps stepper-three row g-3 needs-validation" id="employee-wizard-income-step">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="justify-content-between mb-3">
                                            @php
                                                $savedIncomeIds = $employee?->incomeCategories?->pluck('income_category_id')->map(fn ($id) => (string) $id)->all() ?? [];
                                            @endphp
                                            @foreach ($incomeCategoriesTypes as $incomeType)
                                            @foreach ($incomeType->categories as $category)
                                            @php
                                                $incomeChecked = collect(old('income_category_id', $savedIncomeIds))->contains(fn ($v) => (int) $v === (int) $category->id);
                                            @endphp
                                            <div class="form-group d-flex mb-3 gap-2 toggle-input-wrapper">
                                                <input type="checkbox"
                                                    name="income_category_id[]"
                                                    value="{{ $category->id }}"
                                                    class="form-check-input toggle-checkbox mt-0"
                                                    id="cat_{{ $category->id }}"
                                                    @checked($incomeChecked)>

                                                <label class="form-check-label" for="cat_{{ $category->id }}">
                                                    {{ $category->title }}

                                                </label>
                                                <div class="d-flex  flex-grow-1 justify-content-end align-items-center gap-2">
                                                    ({{ $incomeType->title }})
                                                    <input type="number" min="0" step="0.01" class="form-control toggle-input" name="income_amounts[{{ $category->id }}]" @disabled(!$incomeChecked) @if($incomeChecked) required @endif value="{{ old('income_amounts.'.$category->id, $employee?->incomeCategories?->firstWhere('income_category_id', $category->id)?->amount) }}" style="max-width: 250px;">
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
                                        @php
                                            $oldInput = session()->get('_old_input', []);
                                            if (! count($oldInput)) {
                                                $selectedTaxCategoryIds = $taxCategories->pluck('id')->all();
                                            } elseif (array_key_exists('tax_category_id', $oldInput)) {
                                                $selectedTaxCategoryIds = (array) ($oldInput['tax_category_id'] ?? []);
                                            } else {
                                                $selectedTaxCategoryIds = [];
                                            }
                                        @endphp
                                        <div id="employee-wizard-tax-list" class="justify-content-between mb-3">
                                            @foreach ($taxCategories as $taxCategory)
                                            <div class="tax-category-wrapper mb-3">
                                                <input type="checkbox"
                                                    name="tax_category_id[]"
                                                    value="{{ $taxCategory->id }}"
                                                    class="form-check-input toggle-checkbox mt-0"
                                                    id="tax_category_{{ $taxCategory->id }}"
                                                    @checked(collect($selectedTaxCategoryIds)->contains(fn ($v) => (int) $v === (int) $taxCategory->id))>
                                                <label class="form-check-label" for="tax_category_{{ $taxCategory->id }}">{{ $taxCategory->title }}</label>
                                            </div>
                                            @endforeach
                                        </div>
                                        <p id="employee-wizard-tax-empty" class="d-none mb-0">There are no taxes.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-five row g-3 needs-validation">
                                <div class="row">
                                    <div class="col-6">
                                        <div id="employee-wizard-deduction-list" class="justify-content-between mb-3">
                                            @foreach ($deductionCategories as $deductionCategory)
                                            @php
                                                $deductionChecked = collect(old('deduction_category_id', []))->contains(fn ($v) => (int) $v === (int) $deductionCategory->id);
                                            @endphp
                                            <div class="deduction-category-wrapper mb-3 d-flex toggle-input-wrapper">
                                                <input type="checkbox"
                                                    name="deduction_category_id[]"
                                                    value="{{ $deductionCategory->id }}"
                                                    class="form-check-input toggle-checkbox mt-0"
                                                    id="deduction_category_{{ $deductionCategory->id }}"
                                                    @checked($deductionChecked)>
                                                <label for="deduction_category_{{ $deductionCategory->id }}">{{ $deductionCategory->title }}</label>
                                                <div class="d-flex  flex-grow-1 justify-content-end align-items-center gap-2">
                                                    <label for="deduction_category_{{ $deductionCategory->id }}">{{ $deductionCategory->incomeType->title }}</label>
                                                    <input type="number" min="0" step="0.01" class="form-control toggle-input" name="deduction_amounts[{{ $deductionCategory->id }}]" @disabled(!$deductionChecked) @if($deductionChecked) required @endif value="{{ old('deduction_amounts.'.$deductionCategory->id, '') }}" style="max-width: 250px;">
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                        <p id="employee-wizard-deduction-empty" class="d-none mb-0">There are no deductions.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="steps stepper-six row g-3 needs-validation">
                                <div class="toggle-input-wrapper">
                                    <input type="checkbox"
                                        name="include_in_direct_deposit"
                                        value="1"
                                        class="form-check-input toggle-checkbox mt-0"
                                        id="include_in_direct_deposit"
                                        @checked(old('include_in_direct_deposit', $detail?->include_in_direct_deposit))>
                                    <label for="include_in_direct_deposit">Include In Direct Deposit Process</label>

                                    <fieldset {{ old('include_in_direct_deposit', $detail?->include_in_direct_deposit) ? '' : 'disabled' }} class="toggle-input border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
                                        <legend class="float-none w-auto px-2 mb-2 fs-6">Banking Information</legend>
                                        <div class="row">
                                            <div class="col-12 mb-3">
                                                <label for="account_type">Account Type</label>
                                                <select name="account_type" class="form-control" id="account_type">
                                                    <option value="checking" @selected(old('account_type', $detail?->account_type ?? 'checking') === 'checking')>Checking</option>
                                                    <option value="savings" @selected(old('account_type', $detail?->account_type ?? 'checking') === 'savings')>Savings</option>
                                                </select>
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="bank_routing_number">Bank Routing Number</label>
                                                <input type="text"
                                                    name="bank_routing_number"
                                                    id="bank_routing_number"
                                                    class="form-control"
                                                    value="{{ old('bank_routing_number', $detail?->bank_routing_number) }}"
                                                    inputmode="numeric"
                                                    autocomplete="off"
                                                    minlength="9"
                                                    maxlength="9"
                                                    pattern="[0-9]{9}"
                                                    title="Enter all 9 digits of the routing number"
                                                    data-minlength-message="Routing number must be 9 digits"
                                                    oninput="this.value = this.value.replace(/\D/g, '').slice(0, 9)">
                                            </div>
                                            <div class="col-12 mb-3">
                                                <label for="account_number">Account Number</label>
                                                <input type="text"
                                                    name="account_number"
                                                    id="account_number"
                                                    class="form-control"
                                                    value="{{ old('account_number', $detail?->account_number) }}"
                                                    minlength="23"
                                                    maxlength="23"
                                                    inputmode="numeric"
                                                    pattern="[0-9]{23}"
                                                    data-minlength-message="Account number must be 23 digits"
                                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')">
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
                                            <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_check" value="per_check" @checked(old('vacation_sick_calculation_method', $detail?->vacation_sick_calculation_method ?? 'per_check') === 'per_check')>
                                            <label class="form-check-label" for="vacation_sick_method_per_check">Per Check</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input mt-0" type="radio" name="vacation_sick_calculation_method" id="vacation_sick_method_per_total_hours" value="per_total_hours" @checked(old('vacation_sick_calculation_method', $detail?->vacation_sick_calculation_method ?? 'per_check') === 'per_total_hours')>
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
                                            value="{{ old('vacation_hours_earned_per_unit', $detail?->vacation_hours_earned_per_unit !== null ? number_format((float) $detail->vacation_hours_earned_per_unit, 2, '.', '') : '0.00') }}"
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
                                                value="{{ old('max_vacation_hours_per_year', $detail?->max_vacation_hours_per_year !== null ? number_format((float) $detail->max_vacation_hours_per_year, 2, '.', '') : '') }}"
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
                                            value="{{ old('sick_hours_earned_per_unit', $detail?->sick_hours_earned_per_unit !== null ? number_format((float) $detail->sick_hours_earned_per_unit, 2, '.', '') : '0.00') }}"
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
                                                value="{{ old('max_sick_hours_per_year', $detail?->max_sick_hours_per_year !== null ? number_format((float) $detail->max_sick_hours_per_year, 2, '.', '') : '') }}"
                                                placeholder="0.00"
                                                style="max-width: 140px;">
                                            <small class="text-info mb-0 text-muted" style="max-width: 420px;">Keep blank for unlimited hours per year; fill with 0.00 for zero hours per year</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="d-none" id="employee-form-hidden-submit" aria-hidden="true">Submit</button>
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

<x-modals.modal id="incomeModal" title="Payroll Mate" size="modal-lg">
    <p id="employeeWizardConfirmMessage" class="mb-0"></p>

    <x-slot name="footer">
        <button type="button" class="btn btn-secondary" id="employeeWizardConfirmNo">No</button>
        <button type="button" class="btn btn-primary" id="confirmYes">Yes</button>
    </x-slot>
</x-modals.modal>
    @push('scripts')
    @include('includes.js.step-form')
    <script>
        ajaxCreate("{{ route('employees.index') }}");

        (function() {
            const TAX_SETUP_STEP_INDEX = 1;
            const modalEl = document.getElementById('incomeModal');
            const msgEl = document.getElementById('employeeWizardConfirmMessage');
            const btnYes = document.getElementById('confirmYes');
            const btnNo = document.getElementById('employeeWizardConfirmNo');
            if (!modalEl || !msgEl || !btnYes || !btnNo || typeof bootstrap === 'undefined') {
                return;
            }

            const bsModal = bootstrap.Modal.getOrCreateInstance(modalEl);
            let w4ConfirmPhase = 0;
            let pendingAdvance = null;

            const MSG_W4_SUBMITTED = 'Has this employee submitted a Form W-4 for 2020 or later?';
            const MSG_W4_UNCHECKED = 'The box \'Use new W-4 (2020 & beyond)\' is unchecked, are you sure you want to continue?';

            function resetW4ModalState() {
                w4ConfirmPhase = 0;
                pendingAdvance = null;
            }

            modalEl.addEventListener('hidden.bs.modal', function() {
                resetW4ModalState();
            });

            btnNo.addEventListener('click', function(e) {
                e.preventDefault();
                if (w4ConfirmPhase === 1) {
                    const go = pendingAdvance;
                    resetW4ModalState();
                    bsModal.hide();
                    if (typeof go === 'function') {
                        go();
                    }
                    return;
                }
                bsModal.hide();
            });

            btnYes.addEventListener('click', function(e) {
                e.preventDefault();
                if (w4ConfirmPhase === 1) {
                    w4ConfirmPhase = 2;
                    msgEl.textContent = MSG_W4_UNCHECKED;
                    return;
                }
                if (w4ConfirmPhase === 2) {
                    const go = pendingAdvance;
                    resetW4ModalState();
                    bsModal.hide();
                    if (typeof go === 'function') {
                        go();
                    }
                }
            });

            window.__wizardBeforeNextStep = function(stepIndex, proceed) {
                if (stepIndex !== TAX_SETUP_STEP_INDEX) {
                    proceed();
                    return;
                }
                const useNewW4 = document.getElementById('use_new_w4_2020');
                if (!useNewW4 || useNewW4.checked) {
                    proceed();
                    return;
                }
                pendingAdvance = proceed;
                w4ConfirmPhase = 1;
                msgEl.textContent = MSG_W4_SUBMITTED;
                bsModal.show();
            };
        })();

        function syncEmployeeTaxDeductionByIncome() {
            const incomePanel = document.getElementById('employee-wizard-income-step');
            const taxList = document.getElementById('employee-wizard-tax-list');
            const taxEmpty = document.getElementById('employee-wizard-tax-empty');
            const dedList = document.getElementById('employee-wizard-deduction-list');
            const dedEmpty = document.getElementById('employee-wizard-deduction-empty');
            if (!incomePanel || !taxList || !taxEmpty || !dedList || !dedEmpty) {
                return;
            }
            let anyIncome = false;
            incomePanel.querySelectorAll('input[type="checkbox"]').forEach(function(cb) {
                if (cb.name === 'income_category_id[]' && cb.checked) {
                    anyIncome = true;
                }
            });
            if (!anyIncome) {
                taxList.classList.add('d-none');
                taxEmpty.classList.remove('d-none');
                dedList.classList.add('d-none');
                dedEmpty.classList.remove('d-none');
            } else {
                taxList.classList.remove('d-none');
                taxEmpty.classList.add('d-none');
                dedList.classList.remove('d-none');
                dedEmpty.classList.add('d-none');
            }
        }

        document.addEventListener('wizard:step-changed', function() {
            syncEmployeeTaxDeductionByIncome();
        });

        const incomeStepEl = document.getElementById('employee-wizard-income-step');
        if (incomeStepEl) {
            incomeStepEl.addEventListener('change', function(e) {
                const t = e.target;
                if (t && t.type === 'checkbox' && t.name === 'income_category_id[]') {
                    syncEmployeeTaxDeductionByIncome();
                }
            });
        }
        syncEmployeeTaxDeductionByIncome();

        document.querySelectorAll('.toggle-input-wrapper').forEach(function(wrapper) {
            const checkbox = wrapper.querySelector('.toggle-checkbox');
            const target = wrapper.querySelector('.toggle-input');
            if (!checkbox || !target) {
                return;
            }

            function syncToggleAmountRequired() {
                const on = checkbox.checked;
                const isFormControl = target.matches('input, select, textarea');
                if (isFormControl) {
                    target.disabled = !on;
                    if (on) {
                        target.setAttribute('required', 'required');
                    } else {
                        target.removeAttribute('required');
                        target.classList.remove('is-invalid');
                        const n = target.nextElementSibling;
                        if (n && n.classList && n.classList.contains('field-error-msg')) {
                            n.remove();
                        }
                    }
                } else {
                    target.disabled = !on;
                }
            }

            checkbox.addEventListener('change', syncToggleAmountRequired);
            syncToggleAmountRequired();
        });

        function syncDirectDepositBanking() {
            const cb = document.getElementById('include_in_direct_deposit');
            if (!cb) {
                return;
            }
            const wrapper = cb.closest('.toggle-input-wrapper');
            const fieldset = wrapper && wrapper.querySelector('fieldset.toggle-input');
            const routing = document.getElementById('bank_routing_number');
            const account = document.getElementById('account_number');
            if (!fieldset || !routing || !account) {
                return;
            }

            const on = cb.checked;
            fieldset.disabled = !on;

            if (on) {
                routing.setAttribute('required', 'required');
                account.setAttribute('required', 'required');
            } else {
                routing.removeAttribute('required');
                account.removeAttribute('required');
                [routing, account].forEach(function(f) {
                    f.classList.remove('is-invalid');
                    var n = f.nextElementSibling;
                    if (n && n.classList && n.classList.contains('field-error-msg')) {
                        n.remove();
                    }
                });
            }
        }

        const includeDirectDeposit = document.getElementById('include_in_direct_deposit');
        if (includeDirectDeposit) {
            includeDirectDeposit.addEventListener('change', syncDirectDepositBanking);
            syncDirectDepositBanking();
        }

        (function() {
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

        (function() {
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
                [step2, step3, step4a, step4b].forEach(function(el) {
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
    </script>
    @endpush
    @endsection