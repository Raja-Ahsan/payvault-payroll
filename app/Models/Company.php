<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }

    public function federalTaxInformation()
    {
        return $this->hasOne(FederalTaxInformation::class);
    }

    public function stateTaxInformation()
    {
        return $this->hasOne(StateTaxInformation::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
