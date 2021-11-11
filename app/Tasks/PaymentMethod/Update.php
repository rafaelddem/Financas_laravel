<?php

namespace App\Tasks\PaymentMethod;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;

class Update
{
    public function run(PaymentMethodRequest $request)
    {
        $paymentMethod = PaymentMethod::find($request->id);
        $paymentMethod->name = $request->name;
        $paymentMethod->active = boolval($request->active);
        $paymentMethod->update();

        return $paymentMethod;
    }
}