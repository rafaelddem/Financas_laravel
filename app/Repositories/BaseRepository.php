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

    public function get()
    {
        return $this->model->get();
    }

    public function find(int $id)
    {
        try {
            return $this->model->findOrFail($id);
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
}
