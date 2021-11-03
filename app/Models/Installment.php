<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    public function movement()
    {
        return $this->belongsTo(Movement::class, 'movement');
    }

    public function wallet()
    {
        return $this->hasOne(Wallet::class);
    }

    public function sourceWallet()
    {
        return $this->belongsTo(Wallet::class, 'source_wallet');
    }

    public function destinationWallet()
    {
        return $this->belongsTo(Wallet::class, 'destination_wallet');
    }

    public function __toString()
    {
        $installments = $this->movement()->getResults()->installments;
        $installment_number = $this->installment_number;
        $description = $this->movement()->getResults()->title;

        if ($installments > 1) 
            $description .= ' ('.$installment_number.' de '.$installments.')';

        return $description;
    }
}
