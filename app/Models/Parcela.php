<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    public function movimento()
    {
        return $this->belongsTo(Movimento::class);
    }

    public function carteiras()
    {
        return $this->hasOne(Carteira::class);
    }
}
