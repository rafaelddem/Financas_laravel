<?php

namespace App\Repositories;

use App\Models\Transaction;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function list(bool $onlyActive = true)
    {
        return $this->model
            ->with([
                'paymentMethod', 
                'transactionType', 
                'sourceWallet', 
                'destinationWallet', 
            ])
            ->orderBy('transaction_date', 'desc')
            ->get();
    }
}
