<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    public function tipoMovimento()
    {
        return $this->belongsTo(TipoMovimento::class, 'tipoMovimento');
    }

    public function carteiraDestino()
    {
        return $this->belongsTo(Carteira::class);
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }
}
