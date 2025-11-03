<?php

namespace App\Repositories;

use App\Enums\PaymentType;
use App\Exceptions\RepositoryException;
use App\Models\Wallet;
use Carbon\Carbon;

class WalletRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Wallet::class);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->with('owner')
                ->when($onlyActive, function ($query) {
                    $query->where('active', true);
                })
                ->orderby('owner_id', 'asc')
                ->orderby('main_wallet', 'desc')
                ->orderby('active', 'desc')
                ->orderby('name', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listWalletsFromOwner(int $ownerId)
    {
        try {
            return $this->model
                ->with('owner')
                ->when($ownerId, function ($query, $ownerId) {
                    $query->where('owner_id', $ownerId);
                })
                ->orderby('owner_id', 'asc')
                ->orderby('main_wallet', 'desc')
                ->orderby('active', 'desc')
                ->orderby('name', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listWalletsWithCreditCards()
    {
        try {
            return $this->model
                ->select('wallets.*')
                ->distinct('wallets.id')
                ->join('cards', 'cards.wallet_id', '=', 'wallets.id')
                ->where('cards.card_type', PaymentType::Credit->value)
                ->orderby('owner_id', 'asc')
                ->orderby('main_wallet', 'desc')
                ->orderby('active', 'desc')
                ->orderby('name', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function guaranteesSingleMainWallet(Wallet $wallet)
    {
        try {
            if ($wallet->main_wallet) {
                $this->model::query()
                    ->where('owner_id', $wallet->owner_id)
                    ->where('id', '!=', $wallet->id)
                    ->update([ 'main_wallet' => false ]);
            }
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function activateMainWalletFromOwner(int $ownerId)
    {
        try {
            $this->model::query()
                ->where('owner_id', $ownerId)
                ->where('main_wallet', true)
                ->update([ 'active' => true ]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function inactivateWalletsByOwner(int $ownerId)
    {
        try {
            $this->model::query()
                ->where('owner_id', $ownerId)
                ->update([ 'active' => false ]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function getValue(int $invoiceId): float
    {
        try {
            $resultado = \DB::select('CALL calculate_wallet_value_by_invoice(?)', [$invoiceId]);

            return $resultado[0]->total ?? 0;
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function hasOutstandingMonetaryBalancesOnWallet(int $walletId)
    {
        try {
            $plannedTransactions = $this->model
                ->join('transactions', function ($join) {
                    $join->on('transactions.source_wallet_id', '=', 'wallets.id')
                        ->orOn('transactions.destination_wallet_id', '=', 'wallets.id');
                })
                ->where('wallets.id', $walletId)
                ->where('transactions.transaction_date', '>', Carbon::today())
                ->distinct('transactions.id')
                ->count();

            $futureInstallments = $this->model
                ->join('cards', 'cards.wallet_id', '=', 'wallets.id')
                ->join('transactions', 'transactions.card_id', '=', 'cards.id')
                ->leftJoin('installments', 'installments.transaction_id', '=', 'transactions.id')
                ->where('cards.card_type', PaymentType::Credit->value)
                ->whereNotNull('installments.payment_date')
                ->where('cards.wallet_id', $walletId)
                ->count();

            return $plannedTransactions + $futureInstallments;
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
