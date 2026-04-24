<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StateReportingMethodOption extends Model
{
    /** @var list<string> */
    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    public function taxType(): BelongsTo
    {
        return $this->belongsTo(StateReportingTaxType::class, 'state_reporting_tax_type_id');
    }
}
