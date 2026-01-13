<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollDeduction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_item_id',
        'deduction_type',
        'description',
        'calculation_type',
        'amount',
        'percentage',
        'is_pre_tax',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'percentage' => 'decimal:2',
            'is_pre_tax' => 'boolean',
        ];
    }

    /**
     * Get the payroll item that owns the deduction.
     */
    public function payrollItem(): BelongsTo
    {
        return $this->belongsTo(PayrollItem::class);
    }
}
