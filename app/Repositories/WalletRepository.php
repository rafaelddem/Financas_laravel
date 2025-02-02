<?php

namespace App\Repositories;

use App\Models\Wallet;

class WalletRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Wallet::class);
    }

    public function list()
    {
        return $this->model
            ->with('owner')
            ->orderby('owner_id', 'asc')
            ->orderby('main_wallet', 'desc')
            ->orderby('name', 'asc')
            ->get();
    }

    public function guaranteesSingleMainWallet(Wallet $wallet)
    {
        if ($wallet->main_wallet) {
            Wallet::query()
                ->where('owner_id', $wallet->owner_id)
                ->where('id', '!=', $wallet->id)
                ->update([ 'main_wallet' => false ]);
        }
    }
}
