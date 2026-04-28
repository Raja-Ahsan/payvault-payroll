<?php

namespace App\Services\Payroll;

use App\Models\Employee;
use App\Models\PayrollCheck;

/**
 * Builds check-create form defaults from persisted employee / company setup (no check history yet).
 */
class EmployeeCheckScaffoldService
{
    private const INCOME_SLOT_BY_TITLE = [
        'regular hourly pay' => 'regular_hourly',
        'overtime hourly pay' => 'overtime_hourly',
        'yearly salary' => 'yearly_salary',
        'double-time' => 'double_time',
    ];

    /**
     * @return array<string, mixed>
     */
    public function forEmployee(Employee $employee): array
    {
        $employee->load([
            'detail',
            'incomeCategories.incomeCategory.incomeType',
        ]);

        $detail = $employee->detail;

        $payFrequency = $detail?->pay_frequency ?: 'weekly_52';

        $income = $this->emptyIncomeSlots();

        $extraIncomes = [];

        foreach ($employee->incomeCategories as $row) {
            $category = $row->incomeCategory;
            if (! $category) {
                continue;
            }

            $slot = $this->incomeSlotForCategoryTitle((string) $category->title);
            $payType = $this->payTypeFromIncomeTypeTitle($category->incomeType?->title);

            $payload = [
                'income_category_id' => $category->id,
                'title' => $category->title,
                'rate' => $this->formatDecimal((float) $row->amount),
                'pay_type' => $payType,
                'quantity' => '',
                'amount' => '',
                'ytd' => '',
            ];

            if ($slot !== null) {
                $income[$slot] = $payload;
            } else {
                $extraIncomes[] = $payload;
            }
        }

        $nextCheckNumber = (int) (PayrollCheck::query()->where('employee_id', $employee->id)->max('check_number'));
        $nextCheckNumber = max(1, $nextCheckNumber + 1);

        return [
            'employee' => [
                'id' => $employee->id,
                'display_name' => $this->displayName($employee),
                'company_id' => $employee->company_id,
            ],
            'next_check_number' => $nextCheckNumber,
            'pay_frequency' => $payFrequency,
            'pay_frequency_label' => $this->payFrequencyLabel($payFrequency),
            'withholding' => $this->withholdingSnapshot($detail),
            'income' => $income,
            'extra_income_categories' => $extraIncomes,
            'leave_policy' => $this->leavePolicy($detail),
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function emptyIncomeSlots(): array
    {
        $blank = [
            'income_category_id' => null,
            'title' => '',
            'rate' => '',
            'pay_type' => 'per_hour',
            'quantity' => '',
            'amount' => '',
            'ytd' => '',
        ];

        return [
            'regular_hourly' => $blank,
            'overtime_hourly' => $blank,
            'yearly_salary' => array_replace($blank, ['pay_type' => 'per_year']),
            'double_time' => $blank,
        ];
    }

    private function incomeSlotForCategoryTitle(string $title): ?string
    {
        $key = mb_strtolower(trim($title));

        return self::INCOME_SLOT_BY_TITLE[$key] ?? null;
    }

    private function payTypeFromIncomeTypeTitle(?string $incomeTypeTitle): string
    {
        return match ($incomeTypeTitle) {
            'Per Year' => 'per_year',
            'Per Hour', 'Per Piece', 'Per Mile' => 'per_hour',
            default => 'per_hour',
        };
    }

    private function displayName(Employee $employee): string
    {
        $last = trim((string) $employee->last_name);
        $first = trim((string) $employee->first_name);
        $middle = trim((string) ($employee->middle_name ?? ''));

        $rest = $middle !== '' ? "{$first} {$middle}" : $first;

        if ($last !== '' && $rest !== '') {
            return "{$last}, {$rest}";
        }

        return $last !== '' ? $last : ($rest !== '' ? $rest : 'Employee #'.$employee->id);
    }

    private function payFrequencyLabel(string $code): string
    {
        return match ($code) {
            'daily_260' => 'Daily (260 pay periods)',
            'weekly_52' => 'Weekly (52 Pay Periods)',
            'biweekly_26' => 'Bi-Weekly (26 Pay Periods)',
            'semimonthly_24' => 'Semi-Monthly (24 Pay Periods)',
            'monthly_12' => 'Monthly (12 Pay Periods)',
            'quarterly_4' => 'Quarterly (4 pay periods)',
            'annual_1' => 'Annual (1 pay period)',
            default => 'Weekly (52 Pay Periods)',
        };
    }

    /**
     * @return array<string, mixed>|null
     */
    private function withholdingSnapshot(?\App\Models\EmployeeDetail $detail): ?array
    {
        if ($detail === null) {
            return null;
        }

        return [
            'fed_filing_status' => $detail->fed_filing_status,
            'fed_allowances' => $detail->fed_allowances,
            'additional_fed_withholding' => $this->formatDecimal((float) $detail->additional_fed_withholding),
            'use_new_w4_2020' => (bool) $detail->use_new_w4_2020,
            'w4_step2_two_jobs' => (bool) $detail->w4_step2_two_jobs,
            'w4_step3_dependents' => $this->formatDecimal((float) $detail->w4_step3_dependents),
            'w4_step4a_other_income' => $this->formatDecimal((float) $detail->w4_step4a_other_income),
            'w4_step4b_deductions' => $this->formatDecimal((float) $detail->w4_step4b_deductions),
            'tax_zero_federal_income' => (bool) $detail->tax_zero_federal_income,
            'tax_zero_state_income' => (bool) $detail->tax_zero_state_income,
            'tax_zero_ss_med_employee' => (bool) $detail->tax_zero_ss_med_employee,
            'tax_zero_ss_med_employer' => (bool) $detail->tax_zero_ss_med_employer,
            'withholding_state_id' => $detail->withholding_state_id,
            'additional_state_withholding' => $this->formatDecimal((float) $detail->additional_state_withholding),
            'state_filing_status' => $detail->state_filing_status,
            'state_personal_allowances' => $detail->state_personal_allowances,
            'state_dependent_allowances' => $detail->state_dependent_allowances,
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function leavePolicy(?\App\Models\EmployeeDetail $detail): ?array
    {
        if ($detail === null) {
            return null;
        }

        return [
            'vacation_sick_calculation_method' => $detail->vacation_sick_calculation_method,
            'vacation_hours_earned_per_unit' => $this->formatDecimal((float) $detail->vacation_hours_earned_per_unit),
            'max_vacation_hours_per_year' => $detail->max_vacation_hours_per_year !== null
                ? $this->formatDecimal((float) $detail->max_vacation_hours_per_year)
                : '',
            'sick_hours_earned_per_unit' => $this->formatDecimal((float) $detail->sick_hours_earned_per_unit),
            'max_sick_hours_per_year' => $detail->max_sick_hours_per_year !== null
                ? $this->formatDecimal((float) $detail->max_sick_hours_per_year)
                : '',
        ];
    }

    private function formatDecimal(float $value): string
    {
        if (abs($value - round($value)) < 0.000001) {
            return (string) (int) round($value);
        }

        return rtrim(rtrim(number_format($value, 4, '.', ''), '0'), '.');
    }
}
