<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\ExtractModule;

class ExtractModuleRepository extends BaseRepository
{
    public function __construct()
    {
        parent::__construct(ExtractModule::class);
    }

    public function list(bool $onlyActive = true)
    {
        try {
            return $this->model
                ->with(['transactionBaseIn', 'transactionBaseOut'])
                ->orderby('id', 'asc')
                ->get();
        } catch (\Throwable $th) {
            throw new RepositoryException();
        }
    }
}
