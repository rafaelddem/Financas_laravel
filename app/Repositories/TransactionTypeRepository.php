<?php

namespace App\Repositories;

use App\Models\TransactionType;

class TransactionTypeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(TransactionType::class);
    }

    public function list()
    {
        return $this->model
            ->orderby('active', 'desc')
            ->orderby('id', 'asc')
            ->get();
    }

    /**
     * Implementar função após implementação da Transação
     */
    public function hasRelatedTransactions(int $transactionTypeId)
    {
        return false;
    }
}
