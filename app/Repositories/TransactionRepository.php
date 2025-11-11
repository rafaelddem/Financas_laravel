<?php

namespace App\Repositories;

use App\Enums\PaymentType;
use App\Exceptions\RepositoryException;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->with([
                    'paymentMethod', 
                    'category', 
                    'sourceWallet', 
                    'destinationWallet', 
                ])
                ->orderBy('transaction_date', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function ownerLoansTransactions(int $ownerId, Carbon $startDate, Carbon $endDate)
    {
        try {
            $myId = env('MY_OWNER_ID');

            return $this->model
                ->with([
                    'paymentMethod', 
                    'category', 
                    'sourceWallet', 
                    'destinationWallet', 
                ])
                ->leftJoin('wallets as source_wallet', 'source_wallet.id', '=', 'transactions.source_wallet_id')
                ->leftJoin('wallets as destination_wallet', 'destination_wallet.id', '=', 'transactions.destination_wallet_id')
                ->leftJoin('owners as source_owner', 'source_owner.id', '=', 'source_wallet.owner_id')
                ->leftJoin('owners as destination_owner', 'destination_owner.id', '=', 'destination_wallet.owner_id')
                ->whereIn('source_owner.id', [$myId, $ownerId])
                ->whereIn('destination_owner.id', [$myId, $ownerId])
                ->whereColumn('source_owner.id', '!=', 'destination_owner.id')
                ->whereBetween('processing_date', [$startDate, $endDate])
                ->orderBy('transaction_date', 'asc')
                ->orderBy('transactions.id', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function futureCreditValue()
    {
        try {
            return $this->model
                ->selectRaw('COALESCE(SUM(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value), 0) as sum_net_value')
                ->join('installments', 'installments.transaction_id', '=', 'transactions.id')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->whereNull('installments.payment_date')
                ->where('payment_methods.type', PaymentType::Credit->value)
                ->value('sum_net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
