<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimento extends Model
{
    public function tipoMovimento()
    {
        return $this->belongsTo('App\Models\TipoMovimento', 'tipoMovimento');
    }

    public function carteiraDestino()
    {
        return $this->belongsTo(Carteira::class);
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }

    public function __toString()
    {
        $data = Carbon::createFromFormat('Y-m-d H:i:s', $this->dataMovimento)->format('d/m/Y');
        $valor = $this->valorFinal;
        $tipo = get_class($this->tipoMovimento);//->nome;
        return $data . ' | ' . $valor . ' - ' . $tipo;
    }
}
