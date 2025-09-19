<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Card;

class CardRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(Card::class);
    }

    public function listDebitCredit(int $wallet_id, ?string $enum = null)
    {
        try {
            return $this->model
                ->when($enum, function ($query) use ($enum) {
                    $query->where('card_type', $enum);
                })
                ->where('wallet_id', $wallet_id)
                ->where('active', true)
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
