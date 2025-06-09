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
            ->orderby('main_wallet', 'desc')
            ->orderby('active', 'desc')
            ->orderby('name', 'asc')
            ->get();
    }

    public function listWalletsFromOwner(int $ownerId)
    {
        return $this->model
            ->with('owner')
            ->when($ownerId, function ($query, $ownerId) {
                $query->where('owner_id', $ownerId);
            })
            ->orderby('main_wallet', 'desc')
            ->orderby('active', 'desc')
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

    public function activateMainWalletFromOwner(int $ownerId)
    {
        Wallet::query()
            ->where('owner_id', $ownerId)
            ->where('main_wallet', true)
            ->update([ 'active' => true ]);
    }

    public function inactivateWalletsByOwner(int $ownerId)
    {
        Wallet::query()
            ->where('owner_id', $ownerId)
            ->update([ 'active' => false ]);
    }

    /**
     * Implementar função após implementação da Transação
     */
    public function hasOutstandingMonetaryBalancesOnWallet(int $walletId)
    {
        return false;
    }
}
