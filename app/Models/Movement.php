<?php

namespace App\Models;

use App\Tasks\Financial\Money;
use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
    public function movement_type()
    {
        return $this->belongsTo(MovementType::class, 'movement_type');
    }

    public function installments()
    {
        return $this->hasMany(Installment::class, 'movement');
    }

    public function getGross_ValueAttribute($gross_value)
    {
        return new Money($gross_value);
    }

    public function getDescount_ValueAttribute($descount_value)
    {
        return new Money($descount_value);
    }

    public function getRounding_ValueAttribute($rounding_value)
    {
        return new Money($rounding_value);
    }

    public function getNetValueAttribute($net_value)
    {
        return new Money($net_value);
    }
}
