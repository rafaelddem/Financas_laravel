<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Installment;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

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
                    'installment_number' => ($intallmentNumber + 1), 
                    'installment_total' => count($input)
                ]);
            }
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function updateInstallmentDate(Invoice $invoice): float
    {
        try {
            $installments = $this->model
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->where('transactions.card_id', $invoice->card_id)
                ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date]);
            $installments->update(['installments.installment_date' => $invoice->start_date]);

            return $installments->get()->sum('net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function installmentsByInvoice(int $cardId, Carbon $start_date, Carbon $end_date): Collection
    {
        try {
            return $this->model
                ->select('installments.*')
                ->with('transaction.destinationWallet.owner')
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->where('transactions.card_id', $cardId)
                ->whereBetween('installment_date', [$start_date, $end_date])
                ->orderBy('installment_date')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function updateInstallmentPaymentDate(Invoice $invoice, Carbon $paymentDate)
    {
        try {
            $this->model
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->where('transactions.card_id', $invoice->card_id)
                ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date])
                ->update(['installments.payment_date' => $paymentDate]);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function getSumByInvoice(Invoice $invoice): float
    {
        try {
            return $this->model
                ->join('transactions', 'transactions.id', '=', 'installments.transaction_id')
                ->join('cards', 'cards.id', '=', 'transactions.card_id')
                ->selectRaw('COALESCE(SUM(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value), 0) as sum')
                ->where('card_id', $invoice->card_id)
                ->whereBetween('installment_date', [$invoice->start_date, $invoice->end_date])
                ->value('sum');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
