<?php

namespace App\Traits;

use App\Enums\PaymentType;
use App\Exceptions\ServiceException;
use App\Models\Invoice;
use App\Models\Transaction;
use Carbon\Carbon;

trait TransactionValidations
{
    private function validateNewTransaction(array $transactionValues)
    {
        $net_value = $this->validateTransactionValues($transactionValues);

        $this->validCardWithWallet($transactionValues);

        if (isset($transactionValues['installments'])) {
            $this->validateInstallmentsValues($net_value, $transactionValues['installments']);
        }
    }
    private function validateTransactionChanges(array $transactionValues, Transaction $transaction, ?Invoice $openInvoice = null)
    {
        $this->transactionHasPermissionUpdate($transaction);

        $this->transactionAcceptNewData($transaction, $transactionValues);

        $net_value = $this->validateTransactionValues($transactionValues);

        if (isset($transactionValues['installments'])) {
            $this->transactionAcceptNewInstallment($transaction, $transactionValues['installments'], $openInvoice);

            $this->validateInstallmentsValues($net_value, $transactionValues['installments']);
        }
    }

    public function validPaymentType(Transaction $transaction, PaymentType $paymentType): bool
    {
        return $transaction->paymentMethod->type == $paymentType;
    }

    public function validCardWithWallet(array $transactionValues)
    {
        if (isset($transactionValues['card_id'])) {
            if (isset($transactionValues['card_wallet_id']) && $transactionValues['source_wallet_id'] == $transactionValues['card_wallet_id']) 
                return;

            throw new ServiceException(__('The selected Card must belong to the Source Wallet.'));
        }
    }

    private function validateTransactionValues(array $transactionValues): float
    {
        $gross_value = $transactionValues['gross_value'];
        $discount_value = $transactionValues['discount_value'] ?? 0;
        $interest_value = $transactionValues['interest_value'] ?? 0;
        $rounding_value = $transactionValues['rounding_value'] ?? 0;
        $net_value = $gross_value - $discount_value + $interest_value + $rounding_value;

        if ($net_value <= 0) 
            throw new ServiceException(__('The sum of values of the Transaction cannot be zero.'));

        return $net_value;
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
        //Permite a atualização de vendas no crédito por que a validação será depois, considerando Faturas fechadas ou não
        if ($this->validPaymentType($transaction, PaymentType::Credit)) 
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
}