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
            if ($this->repository->hasRelatedTransactions($id)) 
                throw new ServiceException('It is not allowed to remove a payment method that is linked to a transaction.');

            $this->repository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
