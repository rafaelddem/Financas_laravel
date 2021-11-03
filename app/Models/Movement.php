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

    // public function getValorFinalAttribute($valorFinal)
    // {
    //     return (new FormataValor)->run($valorFinal);
    // }

    public function getFormattedNetValue()
    {
        return (new Money)->formatValue($this->net_value);
    }
}
