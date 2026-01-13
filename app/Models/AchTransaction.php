<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AchTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id',
        'bank_account_id',
        'transaction_type',
        'amount',
        'ach_batch_id',
        'transaction_id',
        'status',
        'status_message',
        'processed_at',
        'completed_at',
        'processor_response',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'processed_at' => 'datetime',
            'completed_at' => 'datetime',
            'processor_response' => 'array',
        ];
    }

    /**
     * Get the payroll run that owns the ACH transaction.
     */
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Get the bank account that owns the ACH transaction.
     */
    public function bankAccount(): BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
}
