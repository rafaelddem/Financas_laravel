<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CardRepository;
use App\Repositories\InstallmentRepository;
use App\Repositories\InvoiceRepository;
use App\Repositories\TransactionRepository;

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

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $transaction = $this->repository->create($input);

            if (isset($input['card_id'])) {
                $card = $this->cardRepository->find($input['card_id']);

                if ($input['source_wallet_id'] != $card->wallet) {
                    throw new ServiceException(__('The selected Card must belong to the Source Wallet.'));
                }
            }

            if (isset($input['installments'])) {
                $this->validateInstallmentsValues($transaction->net_value, $input['installments']);

                $this->installmentRepository->insertMultiples($transaction->id, $input['installments']);

                $this->invoiceRepository->addValueToInvoice($transaction->card_id, $transaction->installments()->orderBy('installment_date')->first()->net_value);
            }

            \DB::commit();
            return $transaction;
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (ServiceException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
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
}
