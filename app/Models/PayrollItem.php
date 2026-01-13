<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PayrollItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_run_id',
        'employee_id',
        'hours_worked',
        'regular_hours',
        'overtime_hours',
        'regular_rate',
        'overtime_rate',
        'gross_pay',
        'federal_tax',
        'state_tax',
        'local_tax',
        'social_security',
        'medicare',
        'total_taxes',
        'total_deductions',
        'net_pay',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'hours_worked' => 'decimal:2',
            'regular_hours' => 'decimal:2',
            'overtime_hours' => 'decimal:2',
            'regular_rate' => 'decimal:2',
            'overtime_rate' => 'decimal:2',
            'gross_pay' => 'decimal:2',
            'federal_tax' => 'decimal:2',
            'state_tax' => 'decimal:2',
            'local_tax' => 'decimal:2',
            'social_security' => 'decimal:2',
            'medicare' => 'decimal:2',
            'total_taxes' => 'decimal:2',
            'total_deductions' => 'decimal:2',
            'net_pay' => 'decimal:2',
        ];
    }

    /**
     * Get the payroll run that owns the payroll item.
     */
    public function payrollRun(): BelongsTo
    {
        return $this->belongsTo(PayrollRun::class);
    }

    /**
     * Get the employee that owns the payroll item.
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get the deductions for the payroll item.
     */
    public function deductions(): HasMany
    {
        return $this->hasMany(PayrollDeduction::class);
    }
}
