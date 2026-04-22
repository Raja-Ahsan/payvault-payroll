<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PayrollCheck extends Model
{
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'pay_date' => 'date',
            'period_begin_date' => 'date',
            'period_end_date' => 'date',
            'income_breakdown' => 'array',
            'calculation_warnings' => 'array',
            'check_preview' => 'array',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
