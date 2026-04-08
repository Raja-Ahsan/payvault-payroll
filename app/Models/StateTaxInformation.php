<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StateTaxInformation extends Model
{
    protected $table = 'state_tax_information';

    protected $guarded = ['id'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
