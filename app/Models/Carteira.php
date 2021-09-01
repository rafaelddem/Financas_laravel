<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carteira extends Model
{
    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function movimentos()
    {
        return $this->hasMany(Movimento::class);
    }

    public function parcelas()
    {
        return $this->hasMany(Parcela::class);
    }



    public function seCarteiraPrincipal()
    {
        return $this->principal;
    }

    public function nomeDono()
    {
        return 'xana';//$this->pessoa()->nome;
    }
}
