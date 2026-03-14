<?php

namespace App\Services;

use App\Enums\InvoiceStatus;
use App\Enums\PaymentType;
use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Installment;
use App\Models\Invoice;
use App\Models\Transaction;
use App\Repositories\CardRepository;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TransactionRepository;
use Carbon\Carbon;

class TransactionService extends BaseService
{
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

    public static function validPaymentType(Transaction $transaction, PaymentType $paymentType): bool
    {
        return $transaction->paymentMethod->type == $paymentType;
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $this->validateTransactionValues($input);

            if (isset($input['card_id'])) {
                $card = $this->cardRepository->find($input['card_id']);

                if ($input['source_wallet_id'] != $card->wallet_id) {
                    throw new ServiceException(__('The selected Card must belong to the Source Wallet.'));
                }
            }

            $transaction = $this->repository->create($input);

            if (isset($input['installments'])) {
                $this->validateInstallmentsValues($transaction->net_value, $input['installments']);

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

            $this->transactionHasPermissionUpdate($transaction);

            $this->transactionAcceptNewData($transaction, $input);

            $this->validateTransactionValues($input);

            $transaction = $this->repository->update($id, $input);

            if (isset($input['installments'])) {
                $openInvoice = $this->invoiceRepository->listInvoices($transaction->startDate->clone()->subMonth(), $transaction->endDate->clone()->addMonth(), InvoiceStatus::Open, null, $transaction->card_id)->first();

                $this->transactionAcceptNewInstallment($transaction, $input['installments'], $openInvoice);

                $this->validateInstallmentsValues($transaction->net_value, $input['installments']);

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

    private function validateTransactionValues(array $transactionValues)
    {
        $gross_value = $transactionValues['gross_value'];
        $discount_value = $transactionValues['discount_value'] ?? 0;
        $interest_value = $transactionValues['interest_value'] ?? 0;
        $rounding_value = $transactionValues['rounding_value'] ?? 0;
        $net_value = $gross_value - $discount_value + $interest_value + $rounding_value;

        if ($net_value <= 0) 
            throw new ServiceException(__('The sum of values of the Transaction cannot be zero.'));
    }

    private function validateInstallmentsValues(float $netValue, array $installmentsData): bool
    {
        $total = 0.0;

        foreach ($installmentsData as $key => $installmentData) {
            $total += $this->validateInstallmentValues(($key + 1), $installmentData);
        }

        if ($total == $netValue) 
            return true;

        throw new ServiceException(__('The sum of the Gross Values of the Installments cannot be greater than the Net Value of the Transaction.'));
    }

    private function validateInstallmentValues(int $installmentNumber, array $installmentData): float
    {
        // Quando da atualização, garantir que a chave usada no array de parcelas é correspondente ao identificado da parcela
        if (isset($installmentData['installment_number']) && $installmentNumber != ($installmentData['installment_number'])) 
            throw new ServiceException(__('Invalid value for field of installment installmentNumber.', ['field' => __('Installment Number'), 'installmentNumber' => $installmentNumber]));

        if ($installmentData['gross_value'] <= $installmentData['discount_value']) 
            throw new ServiceException(__('Invalid value for field of installment installmentNumber.', ['field' => __('Discount Value'), 'installmentNumber' => $installmentNumber]));

        if ($installmentData['gross_value'] <= $installmentData['interest_value']) 
            throw new ServiceException(__('Invalid value for field of installment installmentNumber.', ['field' => __('Interest Value'), 'installmentNumber' => $installmentNumber]));

        if ($installmentData['gross_value'] <= $installmentData['rounding_value']) 
            throw new ServiceException(__('Invalid value for field of installment installmentNumber.', ['field' => __('Rounding Value'), 'installmentNumber' => $installmentNumber]));

        $total = $installmentData['gross_value'] - $installmentData['discount_value'] + $installmentData['interest_value'] + $installmentData['rounding_value'];
        if ($total < 0) 
            throw new ServiceException(__('Sum of installment installmentNumber cannot be negative.', ['installmentNumber' => $installmentNumber]));

        return $installmentData['gross_value'];
    }

    private function transactionHasPermissionUpdate(Transaction $transaction)
    {
        if (self::validPaymentType($transaction, PaymentType::Credit)) 
            return;

        if ($transaction->transaction_date < Carbon::now()->subDays(env('TRANSACTION_LIMIT_DAYS_TO_UPDATE'))) 
            throw new ServiceException(__('You cannot edit a transaction that occurred more than number_of_days days ago.', ['number_of_days' => env('TRANSACTION_LIMIT_DAYS_TO_UPDATE')]));
    }

    private function transactionAcceptNewData(Transaction $transaction, array $newData)
    {
        $newTransaction = new Transaction($newData);

        if ($transaction->payment_method_id != $newTransaction->payment_method_id) 
            throw new ServiceException(__('You cannot edit the field transaction_field from a transaction.', ['transaction_field' => __('Payment Method')]));

        if ($transaction->source_wallet_id != $newTransaction->source_wallet_id) 
            throw new ServiceException(__('You cannot edit the field transaction_field from a transaction.', ['transaction_field' => __('Source Wallet')]));

        if ($transaction->card_id != null && $transaction->card_id != $newTransaction->card_id) 
            throw new ServiceException(__('You cannot edit the field transaction_field from a transaction.', ['transaction_field' => __('Card')]));
    }

    private function transactionAcceptNewInstallment(Transaction $transaction, array $newInstallments, Invoice $openInvoice)
    {
        if ($transaction->installments->count() != count($newInstallments)) 
            throw new ServiceException('You cannot change the number of Installments from a Transaction.');


        throw new ServiceException('A atualização de parcelas ainda não foi finalizada. Favor aguardar a próxima versão');

        /**
         * Melhorar\Finalizar validação para impedir alteração das parcelas que pertencem a faturas já fechadas
         */


        foreach ($newInstallments as $newInstallment) {
            $installment = $transaction->installments->where('installment_number', $newInstallment['installment_number'])->first();
            $newInstallment = new Installment($newInstallment);

            if ($installment->payment_date != null || $installment->installment_date->lt($openInvoice->start_date)) {
                if (
                    $installment->installment_total != $newInstallment->installment_total
                    || $installment->installment_date != $newInstallment->installment_date
                    || $installment->gross_value != $newInstallment->gross_value
                    || $installment->discount_value != $newInstallment->discount_value
                    || $installment->interest_value != $newInstallment->interest_value
                    || $installment->rounding_value != $newInstallment->rounding_value
                    || $installment->payment_date != $newInstallment->payment_date
                ) {
                    dd("Falha");
                }
            }
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
