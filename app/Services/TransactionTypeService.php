<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\TransactionTypeRepository;

class TransactionTypeService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(TransactionTypeRepository::class);
    }

    public function delete(int $id)
    {
        try {
            if ($this->repository->hasRelatedTransactions($id)) 
                throw new ServiceException('It is not allowed to remove a transaction type that is linked to a transaction.');

            $this->repository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
