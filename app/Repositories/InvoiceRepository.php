<?php

namespace App\Repositories;

use App\Enums\InvoiceStatus;
use App\Exceptions\RepositoryException;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class InvoiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Invoice::class);
    }

    public function listInvoices(?InvoiceStatus $status = null)
    {
        try {
            return $this->model
                ->with('card')
                ->when($status, function ($query) use ($status) {
                    $query->where('status', $status->value);
                })
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
