<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    public function carteiras()
    {
        return $this->hasMany(Carteira::class);
    }
}
