<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Invoice;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use Carbon\Carbon;

class InvoiceService extends BaseService
{
    private InstallmentRepository $installmentRepository;

    public function __construct()
    {
        $this->repository = app(InvoiceRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
    }

    public function listInvoices(?InvoiceStatus $status = null)
    {
        try {
            return $this->repository->listInvoices($status);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function createNecessaryInvoices(): void
    {
        try {
            \DB::beginTransaction();

            $invoicesToUpdate = $this->repository->invoicesToUpdate();

            foreach ($invoicesToUpdate as $invoice) {
                $this->closeInvoice($invoice);
                $this->createInvoice($invoice);
            }

            \DB::commit();
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function createInvoice(Invoice $invoice): void
    {
        $start_date = $invoice->end_date->addDay()->startOfDay();
        $end_date = $start_date->clone();

        // Para os casos onde o first_day_month foi alterado
        if ($start_date->day != $invoice->card->first_day_month) {
            $end_date->setDay($invoice->card->first_day_month);
        }

        if ($start_date->gte($end_date)) {
            $end_date->addMonth();
        }

        $end_date->subDay()->endOfDay();

        $newInvoice = Invoice::make([
            'card_id' => $invoice->card_id,
            'start_date' => $start_date->startOfDay(),
            'end_date' => $end_date,
            'due_date' => $end_date->clone()->addDays($invoice->card->days_to_expiration),
        ]);

        $newInvoice->value = $this->installmentRepository->updateInstallmentDate($newInvoice);

        $newInvoice->save();
    }

    public function closeInvoice(Invoice $invoice)
    {
        try {
            $value = $this->installmentRepository->getSumByInvoice($invoice);
            $invoiceStatus = $value > 0
                ? InvoiceStatus::Closed->value
                : InvoiceStatus::Paid->value;

            $this->repository->update($invoice->id, [
                'value' => $value,
                'status' => $invoiceStatus
            ]);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
