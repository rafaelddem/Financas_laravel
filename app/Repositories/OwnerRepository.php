<?php

namespace App\Repositories;

use App\Models\Owner;

class OwnerRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Owner::class);
    }

    /**
     * Implementar função após implementação da Transação
     */
    public function hasOutstandingMonetaryBalancesOnOwner(int $ownerId)
    {
        return false;
    }
}
