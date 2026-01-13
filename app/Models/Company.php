<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'legal_name',
        'ein',
        'address',
        'city',
        'state',
        'zip_code',
        'phone',
        'email',
        'payroll_config',
        'ach_enrolled',
        'ach_status',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'ach_enrolled' => 'boolean',
            'payroll_config' => 'array',
        ];
    }

    /**
     * Get the user that created the company.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the employees for the company.
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    /**
     * Get the payroll runs for the company.
     */
    public function payrollRuns(): HasMany
    {
        return $this->hasMany(PayrollRun::class);
    }

    /**
     * Get the bank accounts for the company.
     */
    public function bankAccounts(): MorphMany
    {
        return $this->morphMany(BankAccount::class, 'accountable');
    }
}
