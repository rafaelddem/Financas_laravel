<?php

namespace App\Services;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Repositories\PaymentMethodRepository;

class PaymentMethodService
{
    private PaymentMethodRepository $repository;

    public function __construct()
    {
        $this->repository = app(PaymentMethodRepository::class);
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

        return [];
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

    public function show(int $id)
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
