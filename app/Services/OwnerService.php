<?php

namespace App\Services;

use App\Exceptions\RepositoryException;
use App\Exceptions\ServiceException;
use App\Repositories\OwnerRepository;
use App\Repositories\WalletRepository;

class OwnerService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(OwnerRepository::class);
    }

    public function create(array $input)
    {
        try {
            \DB::beginTransaction();
            $owner = $this->repository->create($input);

            app(WalletRepository::class)->create([
                'name' => 'Carteira de ' . $owner->name,
                'owner_id' => $owner->id,
                'main_wallet' => true,
                'active' => true,
                'description' => __('Standard Owner\'s Wallet', ['owner' => $owner->name]),
            ]);

            \DB::commit();
            return $owner;
        } catch (RepositoryException $exception) {
            \DB::rollBack();
            throw $exception;
        } catch (\Throwable $th) {
            \DB::rollBack();
            throw new ServiceException();
        }
    }
}
