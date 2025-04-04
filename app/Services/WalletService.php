<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Models\Wallet;
use App\Repositories\OwnerRepository;
use App\Repositories\WalletRepository;
use App\Services\BaseService;

class WalletService extends BaseService
{
    protected OwnerRepository $ownerRepository;

    public function __construct()
    {
        $this->repository = app(WalletRepository::class);
        $this->ownerRepository = app(OwnerRepository::class);
    }

    public function create(array $input)
    {
        try {
            $this->validCreation($input['owner_id']);

            $wallet = $this->repository->create($input);

            $this->repository->guaranteesSingleMainWallet($wallet);

            return $wallet;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function update(int $id, array $input)
    {
        try {
            $wallet = $this->repository->find($id, ['owner']);

            $this->validChange($wallet, $input);

            $wallet = $this->repository->update($id, $input);

            $this->repository->guaranteesSingleMainWallet($wallet);

            return $wallet;
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function listWalletsFromOwner(int $owner_id)
    {
        try {
            return $this->repository->listWalletsFromOwner($owner_id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }

        return [];
    }

    public function delete(int $id)
    {
        try {
            $wallet = $this->repository->find($id, ['owner']);

            if ($wallet->main_wallet) 
                throw new ServiceException('It is not allowed to remove the main Wallet.');

            if ($this->repository->hasOutstandingMonetaryBalancesOnWallet($id)) 
                throw new ServiceException('It is not allowed to remove a Wallet that has outstanding monetary balances.');

            $this->repository->delete($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    /**
     * Valida se as alterações solicitadas não vão contra regras de negócio.
     * Tais validações dependem dos valores originais da entidade, por esse motivo não foram feitas ainda no request
     * 
     * @param int $id
     * @param array $input
     */
    private function validChange(Wallet $wallet, array $input)
    {
        if (
            $wallet->main_wallet
            && isset($input['main_wallet'])
            && !$input['main_wallet']
        ) {
            throw new ServiceException('The main wallet of an account cannot be unmarked as such.');
        }

        if (isset($input['active'])) {
            $input['active'] 
                ? $this->validActivation($wallet)
                : $this->validInactivation($wallet);
        }

        return true;
    }

    private function validCreation(int $owner_id)
    {
        if ($this->ownerRepository->find($owner_id)->active) 
            return true;

        throw new ServiceException('It is not allowed to create a Wallet from inactive Owners.');
    }

    private function validActivation(Wallet $wallet)
    {
        if ($wallet->owner->active) 
            return true;

        throw new ServiceException('It is not allowed to activate a Wallet whose Owner is inactive.');
    }

    private function validInactivation(Wallet $wallet)
    {
        if ($wallet->main_wallet) 
            throw new ServiceException('It is not allowed to inactivate the main Wallet.');

        if ($this->repository->hasOutstandingMonetaryBalancesOnWallet($wallet->id)) 
            throw new ServiceException('It is not allowed to inactivate a Wallet that has outstanding monetary balances.');

        return true;
    }
}
