<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(string $model)
    {
        $this->model = app($model);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->when($onlyActive, function ($query) {
                    $query->where('active', true);
                })
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function find(int $id, array $with = [])
    {
        try {
            return $this->model->with($with)->findOrFail($id);
        } catch (ModelNotFoundException $exception) {
            throw new RepositoryException('The reported record was not found.', $exception->getCode(), $exception);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function create(array $attributes)
    {
        try {
            return $this->model->create($attributes);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function update(int $id, array $attributes)
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->update($attributes);

            return $model;
        } catch (ModelNotFoundException $exception) {
            throw new RepositoryException('The reported record was not found.', $exception->getCode(), $exception);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function delete(int $id)
    {
        try {
            $model = $this->model->findOrFail($id);
            $model->delete($id);
        } catch (ModelNotFoundException $exception) {
            throw new RepositoryException('The reported record was not found.', $exception->getCode(), $exception);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
