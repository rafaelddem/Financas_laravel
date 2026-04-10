<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\ConfigurationRepository;

class ConfigurationService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(ConfigurationRepository::class);
    }

    public function updateConfigurations(array $input)
    {
        try {
            foreach ($input as $key => $value) {
                $this->repository->updateConfigurations($key, ['value' => $value]);
                config(['services.settings.' . strtolower($key) => $value]);
            }
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }
}
