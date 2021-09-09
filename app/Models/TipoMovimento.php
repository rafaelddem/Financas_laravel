<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoMovimento extends Model
{
    public function movimentos()
    {
        return $this->hasMany(Movimento::class, 'tipoMovimento');
    }
}
