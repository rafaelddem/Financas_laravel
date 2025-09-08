<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\TransactionType;

class TransactionTypeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(TransactionType::class);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->when($onlyActive, function ($query) {
                    $query->where('active', true);
                })
                ->orderby('active', 'desc')
                ->orderby('id', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function hasRelatedTransactions(int $transactionTypeId): bool
    {
        try {
            return $this->model->with('transactions')->find($transactionTypeId)->transactions->count();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
