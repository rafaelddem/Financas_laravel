<?php

namespace App\Repositories;

use App\Enums\InvoiceStatus;
use App\Exceptions\RepositoryException;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Invoice::class);
    }

    public function listInvoices(Carbon $startDate, Carbon $endDate, ?InvoiceStatus $status = null, ?int $walletId = null, ?int $cardId = null)
    {
        try {
            return $this->model
                ->select('invoices.*')
                ->with('card')
                ->join('cards', 'cards.id', '=', 'invoices.card_id')
                ->where('invoices.start_date', '<=', $endDate)
                ->where('invoices.end_date', '>=', $startDate)
                ->when($status, function ($query) use ($status) {
                    $query->where('invoices.status', $status->value);
                })
                ->when($walletId, function ($query) use ($walletId) {
                    $query->where('cards.wallet_id', $walletId);
                })
                ->when($cardId, function ($query) use ($cardId) {
                    $query->where('invoices.card_id', $cardId);
                })
                ->orderby('invoices.start_date', 'desc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function invoicesToUpdate()
    {
        try {
            return $this->model
                ->with('card')
                ->where('end_date', '<', now()->startOfDay())
                ->where('status', 'open')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function addValueToInvoice(int $card_id, float $value)
    {
        try {
            $this->model
                ->select('invoices.*')
                ->join('cards', 'cards.id', '=', 'invoices.card_id')
                ->where('invoices.card_id', $card_id)
                ->update([ 'value' => $value ]);
        } catch (ModelNotFoundException $exception) {
            throw new RepositoryException('The reported record was not found.', $exception->getCode(), $exception);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
