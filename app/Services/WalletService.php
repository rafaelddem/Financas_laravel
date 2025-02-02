<?php

namespace App\Services;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Repositories\WalletRepository;
use App\Services\BaseService;

class WalletService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(WalletRepository::class);
    }

    public function create(array $input)
    {
        try {
            $wallet = $this->repository->create($input);

            $this->repository->guaranteesSingleMainWallet($wallet);

            return $wallet;
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function update(int $id, array $input)
    {
        try {
            $wallet = $this->repository->update($id, $input);

            $this->repository->guaranteesSingleMainWallet($wallet);

            return $wallet;
        } catch (RepositoryException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    private function guaranteesActiveMainWallet(array $input)
    {
        return $input['main_wallet'] && !$input['active'];
    }
}
