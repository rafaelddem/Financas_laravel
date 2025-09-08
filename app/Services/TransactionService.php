<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\InstallmentRepository;
use App\Repositories\TransactionRepository;

class TransactionService extends BaseService
{
    private InstallmentRepository $installmentRepository;

    public function __construct()
    {
        $this->repository = app(TransactionRepository::class);
        $this->installmentRepository = app(InstallmentRepository::class);
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $transaction = $this->repository->create($input);

            if (isset($input['installments'])) {
                $this->validateInstallmentsValues($transaction->netValue(), $input['installments']);

                $this->installmentRepository->insertMultiples($transaction->id, $input['installments']);
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

    public function validateInstallmentsValues(float $netValue, array $installmentsData): bool
    {
        $total = 0.0;

        foreach ($installmentsData as $key => $installmentData) {
            $total += $this->validateInstallmentValues(($key + 1), $installmentData);
        }

        if ($total == $netValue) 
            return true;

        throw new ServiceException(__('The sum of the Gross Values of the Installments cannot be greater than the Net Value of the Transaction.'));
    }

    public function validateInstallmentValues(int $installmentNumber, array $installmentData): float
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
