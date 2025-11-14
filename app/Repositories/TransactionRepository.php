<?php

namespace App\Repositories;

use App\Enums\PaymentType;
use App\Exceptions\RepositoryException;
use App\Models\Transaction;

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
