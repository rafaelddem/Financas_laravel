<?php

namespace App\Repositories;

use App\Models\TransactionType;

class TransactionTypeRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(TransactionType::class);
    }
}
