<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\Relevance;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Invoice;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TransactionBaseRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Carbon\Carbon;

class InvoiceService extends BaseService
{
    private TransactionRepository $transactionRepository;
    private InstallmentRepository $installmentRepository;
    private WalletRepository $walletRepository;
    private TransactionBaseRepository $transactionBaseRepository;

    public function __construct()
    {
        $this->repository = app(InvoiceRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
        $this->walletRepository = app(WalletRepository::class);
        $this->transactionBaseRepository = app(TransactionBaseRepository::class);
    }

    public function listInvoices(Carbon $startDate, Carbon $endDate, ?InvoiceStatus $status = null, ?int $walletId = null, ?int $cardId = null)
    {
        try {
            return $this->repository->listInvoices($startDate, $endDate, $status, $walletId, $cardId);
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

                $futureInstallments = $this->installmentRepository->installmentsByInvoice($invoice->card_id, $invoice->end_date->clone()->addDay(), $invoice->end_date->clone()->addYears(5))->count();
                if ($futureInstallments > 0 || $invoice->card->active) {
                    $this->createInvoice($invoice);
                }
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

    private function createInvoice(Invoice $invoice): void
    {
        $start_date = $invoice->end_date->addDay()->startOfDay();
        $end_date = $start_date->clone();

        // Para os casos onde o first_day_month foi alterado
        if ($start_date->day != $invoice->card->first_day_month) 
            $end_date->setDay($invoice->card->first_day_month);

        if ($start_date->gte($end_date)) 
            $end_date->addMonth();

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

    private function closeInvoice(Invoice $invoice)
    {
        $value = $this->installmentRepository->getSumByInvoice($invoice);

        if ($value == 0) {
            $this->repository->update($invoice->id, [
                'value' => $value,
                'status' => InvoiceStatus::Paid->value,
                'payment_date' => Carbon::now(),
            ]);
        } else {
            $this->repository->update($invoice->id, [
                'value' => $value,
                'status' => InvoiceStatus::Closed->value,
            ]);
        }
    }

    public function details(int $invoice_id)
    {
        try {
            $invoice = $this->repository->find($invoice_id, ['card']);

            $installments = $this->installmentRepository->installmentsByInvoice($invoice->card_id, $invoice->start_date, $invoice->end_date);
            $futureInstallments = ($invoice->status == InvoiceStatus::Open->value)
                ? $this->installmentRepository->installmentsByInvoice($invoice->card_id, $invoice->end_date->clone()->addDay()->startOfDay(), $invoice->end_date->clone()->addYears(5))
                : [];

            return [
                $invoice, 
                $installments, 
                $futureInstallments, 
            ];
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function pay(int $invoiceId)
    {
        try {
            \DB::beginTransaction();

            $paymentDate = Carbon::now();
            $invoice = $this->repository->find($invoiceId, ['card']);

            if ($this->walletRepository->getValue($invoiceId) < $invoice->value) 
                throw new ServiceException(__('The wallet does not have enough value for payment.'));

            $this->installmentRepository->updateInstallmentPaymentDate($invoice, $paymentDate);

            $transactionBase = $this->transactionBaseRepository->find(env('INVOICE_TRANSACTION_BASE'));
            $this->transactionRepository->create([
                'title' => $transactionBase->title,
                'transaction_date' => $paymentDate,
                'processing_date' => $paymentDate,
                'category_id' => $transactionBase->category_id,
                'relevance' => Relevance::Indispensable->value,
                'payment_method_id' => $transactionBase->payment_method_id,
                'source_wallet_id' => $invoice->card->wallet_id,
                'destination_wallet_id' => $transactionBase->destination_wallet_id,
                'gross_value' => $invoice->value,
                'discount_value' => 0.00,
                'interest_value' => 0.00,
                'rounding_value' => 0.00,
                'description' => __('Invoice Payment Transaction Description', ['cardName' => $invoice->card->name, 'invoiceStartDate' => $invoice->start_date->format('d/m/Y'), 'invoiceEndDate' => $invoice->end_date->format('d/m/Y')]),
            ]);

            $invoice->update([
                'payment_date' => $paymentDate,
                'status' => InvoiceStatus::Paid->value
            ]);

            \DB::commit();
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }
}
