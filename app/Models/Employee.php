<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    protected static function booted(): void
    {
        static::saving(function (Employee $employee) {
            $key = '401_k_contrib_percent';
            if (! $employee->isDirty($key)) {
                return;
            }
            $raw = (float) $employee->getAttribute($key);
            if ($raw > 100) {
                $employee->setAttribute($key, (int) round(min($raw / 100, 100)));
            }
        });
    }

    /**
     * 401(k) rate is stored as a percentage of gross (e.g. 3 = 3%).
     * If someone entered Excel-style 300 meaning 3%, normalize once: 300 → 3.
     */
    public function effective401kContributionPercent(): float
    {
        $raw = (float) ($this->getAttribute('401_k_contrib_percent') ?? 0);
        if ($raw <= 0) {
            return 0.0;
        }
        if ($raw > 100) {
            $raw /= 100;
        }

        return min($raw, 100.0);
    }

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'hire_date' => 'date',
            'termination_date' => 'date',
            'salary' => 'decimal:2',
            'hourly_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'tax_information' => 'array',
        ];
    }

    /**
     * Get the company that owns the employee.
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user associated with the employee.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the payroll items for the employee.
     */
    public function payrollItems(): HasMany
    {
        return $this->hasMany(PayrollItem::class);
    }

    /**
     * Get the bank accounts for the employee.
     */
    public function bankAccounts(): MorphMany
    {
        return $this->morphMany(BankAccount::class, 'accountable');
    }

    /**
     * Primary bank account for payroll tables (primary first, then first active).
     * When `bankAccounts` is eager-loaded ordered by is_primary desc, id, uses the first row.
     */
    public function primaryBankAccountForPayroll(): ?BankAccount
    {
        if ($this->relationLoaded('bankAccounts')) {
            return $this->bankAccounts->first();
        }

        return $this->bankAccounts()
            ->where('is_active', true)
            ->orderByDesc('is_primary')
            ->orderBy('id')
            ->first();
    }

    /**
     * Get the employee's full name.
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
