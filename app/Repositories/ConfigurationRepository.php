<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Config;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ConfigurationRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Config::class);
    }

    public function updateConfigurations(string $id, array $attributes)
    {
        try {
            $model = $this->model->find($id);

            if (empty($model)) 
                throw new ModelNotFoundException();

            $model->update($attributes);

            return $model;
        } catch (ModelNotFoundException $exception) {
            throw new RepositoryException('The reported record was not found.', $exception->getCode(), $exception);
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
