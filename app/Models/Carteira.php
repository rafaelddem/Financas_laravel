<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    use HasFactory;

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function episodios()
    {
        return $this->hasMany(Episodio::class);
    }
}
