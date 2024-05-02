<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Owner extends Model
{
    protected $fillable = ['name', 'active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function wallets(): HasMany
    {
        return $this->hasMany(Wallet::class);
    }

    public function haveAmountsToPayOrReceive()
    {
        return $this->wallets->filter(function ($wallet) {
            if ($wallet->haveAmountsToPayOrReceive()) {
                return $wallet;
            }
        })->isNotEmpty();
    }

    public function getMainWallet()
    {
        return $this->wallets->filter(function ($wallet) {
            if ($wallet->main_wallet) {
                return $wallet;
            }
        })->first();
    }
}
