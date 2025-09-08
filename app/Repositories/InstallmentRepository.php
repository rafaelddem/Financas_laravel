<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Installment;
use App\Models\Invoice;

class InstallmentRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Installment::class);
    }

    public function insertMultiples(int $transactionId, array $input)
    {
        try {
            foreach ($input as $intallmentNumber => $installment) {
                $this->create($installment + [
                    'transaction_id' => $transactionId, 
                    'installment_number' => ($intallmentNumber + 1)
                ]);
            }
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function updateInstallmentDate(Invoice $invoice)
    {
        try {
            $this->model
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->where('transactions.card_id', $invoice->card_id)
                ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date])
                ->update(['installments.installment_date' => $invoice->start_date]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
