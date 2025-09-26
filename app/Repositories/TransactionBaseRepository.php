<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\TransactionBase;

class TransactionBaseRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(TransactionBase::class);
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
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
