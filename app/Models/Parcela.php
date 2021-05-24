<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcela extends Model
{
    use HasFactory;

    public function temporadas()
    {
        return $this->hasMany( related: Temporada::class);
    }
}
