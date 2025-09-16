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

    public function updateInstallmentDate(Invoice $invoice): float
    {
        try {
            $installments = $this->model::select('transactions.*')
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->where('transactions.card_id', $invoice->card_id)
                ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date]);
            $installments->update(['installments.installment_date' => $invoice->start_date]);

            return $installments->get()->sum('net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function getSumByInvoice(Invoice $invoice)
    {
        return $this->model
            ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
            ->join('cards', 'cards.id', '=', 'transactions.card_id')
            ->selectRaw('SUM(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value) as sum')
            ->where('card_id', $invoice->card_id)
            ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date])
            ->value('sum');
    }
}
