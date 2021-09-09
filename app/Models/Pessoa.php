<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    public function carteiras()
    {
        return $this->hasMany(Carteira::class, 'pessoa');
    }

    public function carteiraPrincipal()
    {
        $carteiras = $this->carteiras;

        foreach ($carteiras as $carteira) {
            if ($carteira->seCarteiraPrincipal()) {
                return $carteira;
            }
        }

        return $carteiras;




        // $carteiras = $this->carteiras;
        // foreach ($carteiras as $carteira) {
        //     echo $carteira->comment;
        // }
    }

    public function usuarioReferente()
    {
        $nome = $this->nome;

        switch ($nome) {
            case 'Não definida':
                return 0;
                break;
            case 'Rafael':
                return 1;
                break;
            default:
                return 2;
                break;
            }
    }
}
