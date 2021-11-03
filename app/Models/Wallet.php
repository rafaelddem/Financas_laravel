<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public function owner()
    {
        return $this->belongsTo(Owner::class, 'owner');
    }

    public function movement()
    {
        return $this->hasMany(Movement::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    public function ifMainWallet()
    {
        return $this->main_wallet;
    }

    public function ownerName()
    {
        return $this->owner()->getResults()->name;
    }
}
