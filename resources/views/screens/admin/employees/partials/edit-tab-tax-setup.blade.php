@php
    $detail = $employee->detail;
    $useNewW4 = (bool) old('use_new_w4_2020', $detail?->use_new_w4_2020);
@endphp
<fieldset class="border border-secondary rounded-3 px-3 pb-3 mb-3 col-12">
    <legend class="float-none w-auto px-2 mb-2 fs-6">Federal Income Tax Setup</legend>
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
                <label class="form-check-label @if (! $useNewW4) text-muted @endif" for="w4_step2_two_jobs" id="w4_step2_two_jobs_label">Step 2 (2 Jobs)</label>
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
