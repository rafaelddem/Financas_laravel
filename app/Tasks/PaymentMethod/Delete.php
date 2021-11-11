<?php

namespace App\Tasks\PaymentMethod;

use App\Models\PaymentMethod;

class Delete
{
    public function run(int $id)
    {
        $paymentMethod = PaymentMethod::find($id);
        $paymentMethod->delete($id);
    }
}