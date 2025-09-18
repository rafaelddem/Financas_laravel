<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\Relevance;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Invoice;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\WalletRepository;
use Carbon\Carbon;

class InvoiceService extends BaseService
{
    private InstallmentRepository $installmentRepository;
    private WalletRepository $walletRepository;
    private TransactionRepository $transactionRepository;

    public function __construct()
    {
        $this->repository = app(InvoiceRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
        $this->walletRepository = app(WalletRepository::class);
        $this->transactionRepository = app(TransactionRepository::class);
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
        try {
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
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
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

    public function pay(int $invoiceId)
    {
        try {
            \DB::beginTransaction();
            
            $paymentDate = Carbon::now();
            $invoice = $this->repository->find($invoiceId, ['card']);

            if ($this->walletRepository->getValue($invoiceId) < $invoice->value) 
                throw new ServiceException(__('The wallet does not have enough value for payment.'));

            $this->installmentRepository->updateInstallmentPaymentDate($invoice, $paymentDate);

            $this->transactionRepository->create([
                'title' => __('Invoice Payment Transaction Title'),
                'transaction_date' => $paymentDate,
                'processing_date' => $paymentDate,
                'transaction_type_id' => env('INVOICE_DEFAULT_TRANSACTION_TYPE'),
                'relevance' => Relevance::Indispensable->value,
                'payment_method_id' => env('INVOICE_DEFAULT_PAYMENT_METHOD'),
                'source_wallet_id' => $invoice->card->wallet_id,
                'destination_wallet_id' => env('INVOICE_DEFAULT_DESTINATION_WALLET'),
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
