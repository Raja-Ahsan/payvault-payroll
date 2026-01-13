<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'accountable_type',
        'accountable_id',
        'account_type',
        'bank_name',
        'account_holder_name',
        'routing_number',
        'account_number',
        'verification_status',
        'verified_at',
        'is_primary',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'verified_at' => 'datetime',
            'is_primary' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the parent accountable model (Company or Employee).
     */
    public function accountable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Get the ACH transactions for the bank account.
     */
    public function achTransactions(): HasMany
    {
        return $this->hasMany(AchTransaction::class);
    }
}
