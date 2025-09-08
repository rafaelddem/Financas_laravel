<?php

namespace App\Repositories;

use App\Models\Installment;

class InstallmentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Installment::class);
    }

    public function insertMultiples(int $transactionId, array $input)
    {
        foreach ($input as $intallmentNumber => $installment) {
            $this->create($installment + [
                'transaction_id' => $transactionId, 
                'installment_number' => ($intallmentNumber + 1)
            ]);
        }
    }
}
