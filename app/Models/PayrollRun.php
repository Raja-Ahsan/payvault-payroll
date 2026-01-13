<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PayrollRun extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'pay_period_type',
        'pay_period_start',
        'pay_period_end',
        'pay_date',
        'status',
        'total_gross',
        'total_deductions',
        'total_net',
        'created_by',
        'approved_by',
        'approved_at',
        'finalized_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'pay_period_start' => 'date',
            'pay_period_end' => 'date',
            'pay_date' => 'date',
            'total_gross' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'total_net' => 'decimal:2',
            'approved_at' => 'datetime',
            'finalized_at' => 'datetime',
        ];
    }

    /**
     * Get the company that owns the payroll run.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user that created the payroll run.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user that approved the payroll run.
     */
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the payroll items for the payroll run.
     */
    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    /**
     * Get the ACH transactions for the payroll run.
     */
    public function achTransactions(): HasMany
    {
        return $this->hasMany(AchTransaction::class);
    }
}
