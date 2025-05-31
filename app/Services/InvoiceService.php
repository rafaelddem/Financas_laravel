<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Invoice;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class InvoiceService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(InvoiceRepository::class);
    }

    public function generateInvoices(): void
    {
        try {
            $invoicesToUpdate = $this->repository->invoicesToUpdate();

            foreach ($invoicesToUpdate as $invoice) {
                $this->openNewInvoice($invoice);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function openNewInvoice(Invoice $invoice): void
    {
        $this->repository->update($invoice->id, [ 'status' => 'closed' ]);

        $start_date = $invoice->end_date->addDay();
        $end_date = now()->endOfDay()->setDay($invoice->card->first_day_month)->subDay();

        if (Carbon::now()->greaterThan($end_date)) {
            $end_date->addMonth();
        }

        $this->repository->create([
            'card_id' => $invoice->card_id,
            'start_date' => $start_date->startOfDay(),
            'end_date' => $end_date,
            'due_date' => $end_date->clone()->addDays($invoice->card->days_to_expiration),
        ]);
    }
}
