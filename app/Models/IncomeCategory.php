<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    protected $guarded = ['id'];

    public function incomeType()
    {
        return $this->belongsTo(IncomeType::class);
    }

    public function taxCategories()
    {
        return $this->belongsToMany(
            TaxCategory::class,
            'income_category_taxes',
            'income_category_id',
            'tax_category_id'
        )->withTimestamps();
    }

    public function deductionCategories()
    {
        return $this->belongsToMany(
            DeductionCategory::class,
            'income_category_deductions',
            'income_category_id',
            'deduction_category_id'
        )->withTimestamps();
    }
}
