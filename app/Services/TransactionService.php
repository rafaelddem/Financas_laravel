<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentType;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CardRepository;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TransactionRepository;
use App\Traits\TransactionValidations;
use Carbon\Carbon;

class TransactionService extends BaseService
{
    use TransactionValidations;

    private InstallmentRepository $installmentRepository;
    private InvoiceRepository $invoiceRepository;
    private CardRepository $cardRepository;

    public function __construct()
    {
        $this->repository = app(TransactionRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
        $this->invoiceRepository = app(InvoiceRepository::class);
        $this->cardRepository = app(CardRepository::class);
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            if (isset($input['card_id'])) {
                $card = $this->cardRepository->find($input['card_id']);
                $input['card_wallet_id'] = $card->wallet_id;
            }

            $this->validateNewTransaction($input);

            $transaction = $this->repository->create($input);

            if (isset($input['installments'])) {
                $this->installmentRepository->insertMultiples($transaction->id, $input['installments']);

                $invoice = $card->invoices()->get()->last();
                if ($invoice->end_date->gte($transaction->transaction_date)) {
                    $invoiceValue = $this->installmentRepository->getSumByInvoice($invoice);
                    $partialPayments = $this->repository->totalInvoicePartialPayment($invoice);
                    $newInvoiceValue = $invoiceValue - $partialPayments;

                    $this->invoiceRepository->addValueToInvoice($transaction->card_id, $newInvoiceValue);
                }
            }

            \DB::commit();
            return $transaction;
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function update(int $id, array $input)
    {
        try {
            \DB::beginTransaction();

            $transaction = $this->repository->find($id, ['installments', 'card.invoices']);
            $openInvoice = $this->invoiceRepository->listInvoices(
                $transaction->transaction_date->clone()->subMonth(), 
                $transaction->processing_date ? $transaction->processing_date->clone()->addMonth() : $transaction->transaction_date->clone()->addMonth(), 
                InvoiceStatus::Open, 
                null, 
                $transaction->card_id
            )->first();

            $this->validateTransactionChanges($input, $transaction, $openInvoice);

            $transaction = $this->repository->update($id, $input);

            if (isset($input['installments'])) {
                $this->installmentRepository->updateMultiples($transaction->id, $input['installments']);

                if ($openInvoice->end_date->gte($transaction->transaction_date)) {
                    $invoiceValue = $this->installmentRepository->getSumByInvoice($openInvoice);
                    $partialPayments = $this->repository->totalInvoicePartialPayment($openInvoice);
                    $newInvoiceValue = $invoiceValue - $partialPayments;

                    $this->invoiceRepository->addValueToInvoice($transaction->card_id, $newInvoiceValue);
                }
            }

            \DB::commit();
            return $transaction;
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function delete(int $id)
    {
        try {
            $transaction = $this->repository->find($id, ['installments', 'card.invoices']);

            if ($transaction->paymentMethod->type == PaymentType::Credit) {
                $openInvoice = $transaction->card->invoices()->where('status', InvoiceStatus::Open->name)->first();

                if ($openInvoice->start_date > $transaction->transaction_date) 
                    throw new ServiceException('You cannot remove a transaction that belongs to an already closed invoice.');
            }

            $this->repository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listLastTransactionsCreated()
    {
        try {
            return $this->repository->listLastTransactionsCreated();
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }
}
