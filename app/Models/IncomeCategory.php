<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IncomeCategory extends Model
{
    protected $guarded = ['id'];

    public function incomeType()
    {
        return $this->belongsTo(incomeType::class);
    }
}
