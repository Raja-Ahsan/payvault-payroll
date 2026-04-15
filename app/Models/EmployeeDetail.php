<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeDetail extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'additional_fed_withholding' => 'decimal:2',
            'use_new_w4_2020' => 'boolean',
            'w4_step2_two_jobs' => 'boolean',
            'w4_step3_dependents' => 'decimal:2',
            'w4_step4a_other_income' => 'decimal:2',
            'w4_step4b_deductions' => 'decimal:2',
            'w2_statutory_employee' => 'boolean',
            'w2_retirement_plan' => 'boolean',
            'w2_advance_eic' => 'boolean',
            'tax_zero_federal_income' => 'boolean',
            'tax_zero_state_income' => 'boolean',
            'tax_zero_ss_med_employee' => 'boolean',
            'tax_zero_ss_med_employer' => 'boolean',
            'additional_state_withholding' => 'decimal:2',
            'include_in_direct_deposit' => 'boolean',
            'vacation_hours_earned_per_unit' => 'decimal:2',
            'max_vacation_hours_per_year' => 'decimal:2',
            'sick_hours_earned_per_unit' => 'decimal:2',
            'max_sick_hours_per_year' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function withholdingState(): BelongsTo
    {
        return $this->belongsTo(State::class, 'withholding_state_id');
    }
}
