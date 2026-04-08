<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxCategory extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'use_box_19' => 'boolean',
            'inactive' => 'boolean',
            'quarterly_rate_q1' => 'decimal:4',
            'quarterly_rate_q2' => 'decimal:4',
            'quarterly_rate_q3' => 'decimal:4',
            'quarterly_rate_q4' => 'decimal:4',
            'wage_base' => 'decimal:2',
            'max_amount_per_check' => 'decimal:2',
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
            'income_category_taxes',
            'tax_category_id',
            'income_category_id'
        )->withTimestamps();
    }
}
