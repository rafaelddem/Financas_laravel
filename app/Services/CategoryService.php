<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\CategoryRepository;

class CategoryService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(CategoryRepository::class);
    }

    public function delete(int $id)
    {
        try {
            if ($this->repository->hasRelatedTransactions($id)) 
                throw new ServiceException('It is not allowed to remove a Category that is linked to a transaction.');

            $this->repository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
