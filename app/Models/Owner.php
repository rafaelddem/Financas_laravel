<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Owner extends Model
{
    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'owner');
    }

    public function mainWallet()
    {
        $wallets = $this->wallets;

        foreach ($wallets as $wallet) {
            if ($wallet->ifMainWallet()) {
                return $wallet;
            }
        }

        return $wallets;
    }

    public function referentOwner()
    {
        $name = $this->name;

        switch ($name) {
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
