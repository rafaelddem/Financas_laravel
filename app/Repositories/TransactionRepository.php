<?php

namespace App\Repositories;

use App\Enums\PaymentType;
use App\Exceptions\RepositoryException;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;

class TransactionRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Transaction::class);
    }

    public function listLastTransactionsCreated()
    {
        try {
            return $this->model
                ->with([
                    'paymentMethod', 
                    'category', 
                    'sourceWallet', 
                    'destinationWallet', 
                ])
                ->orderBy('id', 'desc')
                ->limit(5)
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listTransactionsByWallets(Carbon $startDate, Carbon $endDate, ?array $wallets = null)
    {
        try {
            return $this->model
                ->with([
                    'paymentMethod', 
                    'category', 
                    'sourceWallet', 
                    'destinationWallet', 
                ])
                ->when($wallets, function ($query) use ($wallets) {
                    $query->where(function ($query) use ($wallets) {
                        $query->whereIn('source_wallet_id', $wallets)
                            ->orWhereIn('destination_wallet_id', $wallets);
                    });
                })
                ->whereBetween('transactions.transaction_date', [$startDate, $endDate])
                ->orderBy('transaction_date', 'desc')
                ->orderBy('id', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function futureInvoiceAmounts()
    {
        try {
            return $this->model
                ->selectRaw('COALESCE(SUM(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value), 0) as sum_net_value')
                ->join('installments', 'installments.transaction_id', '=', 'transactions.id')
                ->leftJoin('payment_methods', 'payment_methods.id', '=', 'transactions.payment_method_id')
                ->whereNull('installments.payment_date')
                ->where('payment_methods.type', PaymentType::Credit->value)
                ->value('sum_net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function totalInvoicePartialPayment(Invoice $invoice)
    {
        try {
            return $this->model
                ->where('category_id', env('INVOICE_PARTIAL_PAYMENT_CATEGORY'))
                ->where('source_wallet_id', $invoice->card->wallet_id)
                ->whereBetween('transactions.processing_date', [$invoice->start_date, $invoice->end_date])
                ->get()->sum('net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
