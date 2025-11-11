<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Owner;

class OwnerRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Owner::class);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->when($onlyActive, function ($query) {
                    $query->where('active', true);
                })
                ->orderby('active', 'desc')
                ->orderby('name', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    public function listOther(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->when($onlyActive, function ($query) {
                    $query->where('active', true);
                })
                ->whereNotIn('id', [env('SYSTEM_ID'), env('MY_OWNER_ID')])
                ->orderby('active', 'desc')
                ->orderby('name', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }

    /**
     * Implementar função após implementação da Transação
     */
    public function hasOutstandingMonetaryBalancesOnOwner(int $ownerId)
    {
        return false;
    }
}
