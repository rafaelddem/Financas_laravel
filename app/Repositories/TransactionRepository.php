<?php

namespace App\Repositories;

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
}
