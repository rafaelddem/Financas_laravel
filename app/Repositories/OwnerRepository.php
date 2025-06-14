<?php

namespace App\Repositories;

use App\Models\Owner;

class OwnerRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Owner::class);
    }

    public function list(bool $onlyActive = true)
    {
        return $this->model
            ->when($onlyActive, function ($query) {
                $query->where('active', true);
            })
            ->orderby('active', 'desc')
            ->orderby('name', 'asc')
            ->get();
    }

    /**
     * Implementar função após implementação da Transação
     */
    public function hasOutstandingMonetaryBalancesOnOwner(int $ownerId)
    {
        return false;
    }
}
