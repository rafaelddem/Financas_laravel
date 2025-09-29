<?php

namespace App\Services;

use App\Repositories\ExtractModuleRepository;

class ExtractModuleService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(ExtractModuleRepository::class);
    }
}
