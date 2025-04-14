<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Invoice;

class InvoiceRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Invoice::class);
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
}
