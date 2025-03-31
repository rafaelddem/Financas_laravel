<?php

namespace App\Repositories;

use App\Models\PaymentMethod;

class PaymentMethodRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(PaymentMethod::class);
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
    public function hasRelatedTransactions(int $paymentMethodId)
    {
        return false;
    }
}
