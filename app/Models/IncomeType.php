<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeType extends Model
{
    protected $guarded = ['id'];

    public function categories()
    {
        return $this->hasMany(IncomeCategory::class, 'income_type_id');
    }
}
