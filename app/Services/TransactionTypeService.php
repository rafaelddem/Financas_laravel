<?php

namespace App\Services;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Repositories\TransactionTypeRepository;

class TransactionTypeService
{
   private TransactionTypeRepository $repository;

    public function __construct()
    {
        $this->repository = app(TransactionTypeRepository::class);
    }

    public function list()
    {
        try {
            return $this->repository->get();
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function create(array $input)
    {
        try {
            return $this->repository->create($input);
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function update(int $id, array $input)
    {
        try {
            return $this->repository->update($id, $input);
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function find(int $id)
    {
        try {
            return $this->repository->find($id);
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
