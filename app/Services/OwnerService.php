<?php

namespace App\Services;

use App\Exceptions\BaseException;
use App\Exceptions\ServiceException;
use App\Repositories\OwnerRepository;
use App\Repositories\WalletRepository;

class OwnerService extends BaseService
{
    private WalletRepository $walletRepository;

    public function __construct()
    {
        $this->repository = app(OwnerRepository::class);
        $this->walletRepository = app(WalletRepository::class);
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();

            $owner = $this->repository->create($input);
            $this->walletRepository->create([
                'name' => __('Standard Owner\'s Wallet', ['owner' => $owner->name]),
                'owner_id' => $owner->id,
                'main_wallet' => true,
                'active' => $owner->active,
                'description' => __('Standard Owner\'s Wallet', ['owner' => $owner->name]),
            ]);

            \DB::commit();
            return $owner;
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }

    public function activate(int $id)
    {
        try {
            $this->repository->update($id, ['active' => true]);

            $this->walletRepository->activateMainWalletFromOwner($id);
        } catch (BaseException $exception) {
            throw $exception;
        } catch (\Throwable $th) {
            throw new ServiceException();
        }
    }

    public function inactivate(int $id)
    {
        try {
            \DB::beginTransaction();
            if ($this->repository->hasOutstandingMonetaryBalancesOnOwner($id)) 
                throw new ServiceException('The record cannot be inactivated because it still has outstanding monetary balances.');

            $this->repository->update($id, ['active' => false]);

            $this->walletRepository->inactivateWalletsByOwner($id);
            \DB::commit();
        } catch (BaseException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }
}
