<?php

namespace App\Http\Requests;

use App\Models\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        $fedStatuses = ['single', 'married_filing_jointly', 'married_filing_separately', 'head_of_household', 'qualifying_surviving_spouse'];
        $payFrequencies = ['daily_260', 'weekly_52', 'biweekly_26', 'semimonthly_24', 'monthly_12', 'quarterly_4', 'annual_1'];
        $stateFilingStatuses = ['single', 'married_joint_two_incomes', 'married_joint_one_income', 'married_filing_separately', 'head_of_household'];
        $vacationMethods = ['per_check', 'per_total_hours'];
        $includeDd = $this->boolean('include_in_direct_deposit');

        $companyExists = Rule::exists('companies', 'id');
        if (userHasRole('employee')) {
            $employee = $this->route('employee');
            if ($employee instanceof Employee) {
                $companyExists = $companyExists->where('id', (int) $employee->company_id);
            }
        } elseif (! userHasRole('admin')) {
            $companyExists = $companyExists->where('user_id', auth()->id());
        }

        return [
            'company_id' => ['required', 'integer', $companyExists],

            'first_name' => ['required', 'string', 'max:255'],
            'middle_name' => ['nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'address_1' => ['nullable', 'string', 'max:255'],
            'address_2' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'state_id' => ['nullable', 'exists:states,id'],
            'zip_code' => ['nullable', 'string', 'max:255'],
            'ssn' => ['nullable', 'string', 'max:255'],
            'dob' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:255'],
            'fax' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'employee_id' => ['nullable', 'string', 'max:255'],
            'message' => ['nullable', 'string'],
            'inactive' => ['boolean'],

            'fed_filing_status' => ['required', Rule::in($fedStatuses)],
            'fed_allowances' => [Rule::requiredIf(! $this->boolean('use_new_w4_2020')), 'nullable', 'integer', 'min:0', 'max:99'],
            'pay_frequency' => ['required', Rule::in($payFrequencies)],
            'additional_fed_withholding' => ['nullable', 'numeric', 'min:0'],
            'use_new_w4_2020' => ['boolean'],
            'w4_step2_two_jobs' => ['boolean'],
            'w4_step3_dependents' => ['nullable', 'numeric', 'min:0'],
            'w4_step4a_other_income' => ['nullable', 'numeric', 'min:0'],
            'w4_step4b_deductions' => ['nullable', 'numeric', 'min:0'],

            'w2_statutory_employee' => ['boolean'],
            'w2_retirement_plan' => ['boolean'],
            'w2_advance_eic' => ['boolean'],

            'tax_zero_federal_income' => ['boolean'],
            'tax_zero_state_income' => ['boolean'],
            'tax_zero_ss_med_employee' => ['boolean'],
            'tax_zero_ss_med_employer' => ['boolean'],

            'withholding_state_id' => ['required', 'exists:states,id'],
            'additional_state_withholding' => ['nullable', 'numeric', 'min:0'],
            'state_filing_status' => ['required', Rule::in($stateFilingStatuses)],
            'state_personal_allowances' => ['nullable', 'integer', 'min:0'],
            'state_dependent_allowances' => ['nullable', 'integer', 'min:0'],

            'income_category_id' => ['nullable', 'array'],
            'income_category_id.*' => ['integer', 'exists:income_categories,id'],
            'income_amounts' => ['nullable', 'array'],
            'income_amounts.*' => ['nullable', 'numeric', 'min:0'],

            'tax_category_id' => ['nullable', 'array'],
            'tax_category_id.*' => ['integer', 'exists:tax_categories,id'],

            'deduction_category_id' => ['nullable', 'array'],
            'deduction_category_id.*' => ['integer', 'exists:deduction_categories,id'],
            'deduction_amounts' => ['nullable', 'array'],
            'deduction_amounts.*' => ['nullable', 'numeric', 'min:0'],

            'include_in_direct_deposit' => ['boolean'],
            'account_type' => [Rule::requiredIf($includeDd), 'nullable', Rule::in(['checking', 'savings'])],
            'bank_routing_number' => [Rule::requiredIf($includeDd), 'nullable', 'digits:9'],
            'account_number' => [Rule::requiredIf($includeDd), 'nullable', 'digits:23'],

            'vacation_sick_calculation_method' => ['required', Rule::in($vacationMethods)],
            'vacation_hours_earned_per_unit' => ['nullable', 'numeric', 'min:0'],
            'max_vacation_hours_per_year' => ['nullable', 'numeric', 'min:0'],
            'sick_hours_earned_per_unit' => ['nullable', 'numeric', 'min:0'],
            'max_sick_hours_per_year' => ['nullable', 'numeric', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        if (! $this->boolean('include_in_direct_deposit')) {
            $this->merge([
                'account_type' => null,
                'bank_routing_number' => null,
                'account_number' => null,
            ]);
        }

        if ($this->input('dob') === '') {
            $this->merge(['dob' => null]);
        }

        foreach (['max_vacation_hours_per_year', 'max_sick_hours_per_year'] as $field) {
            if ($this->input($field) === '') {
                $this->merge([$field => null]);
            }
        }

        $inactiveRaw = $this->input('inactive');
        $inactiveVal = is_array($inactiveRaw) ? end($inactiveRaw) : $inactiveRaw;

        $this->merge([
            'inactive' => filter_var($inactiveVal, FILTER_VALIDATE_BOOLEAN),
            'use_new_w4_2020' => $this->boolean('use_new_w4_2020'),
            'w4_step2_two_jobs' => $this->boolean('w4_step2_two_jobs'),
            'w2_statutory_employee' => $this->boolean('w2_statutory_employee'),
            'w2_retirement_plan' => $this->boolean('w2_retirement_plan'),
            'w2_advance_eic' => $this->boolean('w2_advance_eic'),
            'tax_zero_federal_income' => $this->boolean('tax_zero_federal_income'),
            'tax_zero_state_income' => $this->boolean('tax_zero_state_income'),
            'tax_zero_ss_med_employee' => $this->boolean('tax_zero_ss_med_employee'),
            'tax_zero_ss_med_employer' => $this->boolean('tax_zero_ss_med_employer'),
            'include_in_direct_deposit' => $this->boolean('include_in_direct_deposit'),
        ]);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach ($this->input('income_category_id', []) as $id) {
                $id = (int) $id;
                $raw = $this->input("income_amounts.$id");
                if ($raw === null || $raw === '' || ! is_numeric($raw)) {
                    $validator->errors()->add('income_amounts.'.$id, 'This field is required.');
                }
            }

            foreach ($this->input('deduction_category_id', []) as $id) {
                $id = (int) $id;
                $raw = $this->input("deduction_amounts.$id");
                if ($raw === null || $raw === '' || ! is_numeric($raw)) {
                    $validator->errors()->add('deduction_amounts.'.$id, 'This field is required.');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'bank_routing_number.digits' => 'Routing number must be 9 digits.',
            'account_number.digits' => 'Account number must be 23 digits.',
        ];
    }
}
