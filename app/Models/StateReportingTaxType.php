<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StateReportingTaxType extends Model
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

    public function methodOptions(): HasMany
    {
        return $this->hasMany(StateReportingMethodOption::class)->orderBy('sort_order');
    }
}
