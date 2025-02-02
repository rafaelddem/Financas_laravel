<?php

namespace App\Services;

use App\Repositories\PaymentMethodRepository;

class PaymentMethodService extends BaseService
{
    public function __construct()
    {
        $this->repository = app(PaymentMethodRepository::class);
    }
}
