<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeductionCategory extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'use_w2_box_10' => 'boolean',
            'use_w2_box_12' => 'boolean',
            'use_w2_box_14' => 'boolean',
            'inactive' => 'boolean',
            'quarterly_rate_q1' => 'decimal:4',
            'quarterly_rate_q2' => 'decimal:4',
            'quarterly_rate_q3' => 'decimal:4',
            'quarterly_rate_q4' => 'decimal:4',
            'cutoff' => 'decimal:2',
        ];
    }

    public function incomeType()
    {
        return $this->belongsTo(IncomeType::class);
    }

    public function incomeCategories()
    {
        return $this->belongsToMany(
            IncomeCategory::class,
            'income_category_deductions',
            'deduction_category_id',
            'income_category_id'
        )->withTimestamps();
    }
}
