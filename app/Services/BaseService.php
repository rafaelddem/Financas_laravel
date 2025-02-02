<?php

namespace App\Services;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Repositories\BaseRepository;

class BaseService
{
    protected BaseRepository $repository;

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

    public function list()
    {
        try {
            return $this->repository->list();
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }
}
