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
                ->whereNotNull('processing_date')
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
                ->whereNotNull('processing_date')
                ->whereBetween('transaction_date', [$startDate, $endDate])
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
                ->whereNotNull('processing_date')
                ->whereBetween('transaction_date', [$invoice->start_date, $invoice->end_date])
                ->get()->sum('net_value');
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listLastTransactionsToProcessingCreated(int $limit = 10)
    {
        try {
            return $this->model->select([
                    'transaction_planned_id',
                    'title',
                    'gross_value',
                    'discount_value',
                    'interest_value',
                    'rounding_value',
                    'relevance',
                ])
                ->selectRaw('MIN(transaction_date) as transaction_date')
                ->selectRaw('COUNT(*) as total_planned')
                ->with([
                    'sourceWallet', 
                    'destinationWallet', 
                ])
                ->whereNull('processing_date')
                ->groupBy('transaction_planned_id', 'title', 'gross_value', 'discount_value', 'interest_value', 'rounding_value', 'relevance')
                ->orderBy('transaction_date')
                ->limit($limit)
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function findByTransactionPlannedId(int $transactionPlannedId)
    {
        try {
            return $this->model
                ->where('transaction_planned_id', $transactionPlannedId)
                ->whereNull('processing_date')
                ->orderBy('transaction_planned_id')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function removeByTransactionPlannedId(int $transactionPlannedId)
    {
        try {
            return $this->model
                ->where('transaction_planned_id', $transactionPlannedId)
                ->whereNull('processing_date')
                ->orderBy('transaction_planned_id')
                ->delete();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
