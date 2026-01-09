<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\PaymentMethodRepository;

class PaymentMethodService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(PaymentMethodRepository::class);
    }

    public function delete(int $id)
    {
        try {
            $paymentMethod = $this->repository->find($id);

            if ($this->repository->hasRelatedTransactions($id)) 
                throw new ServiceException('It is not allowed to remove a Payment Method that is linked to a transaction.');

            $paymentMethod->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
