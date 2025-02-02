<?php

namespace App\Services;

use App\Repositories\TransactionTypeRepository;

class TransactionTypeService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(TransactionTypeRepository::class);
    }
}
