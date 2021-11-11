<?php

namespace App\Tasks\PaymentMethod;

use App\Http\Requests\PaymentMethodRequest;
use App\Models\PaymentMethod;

class Insert
{
    public function run(PaymentMethodRequest $request)
    {
        $paymentMethod = new PaymentMethod();
        $paymentMethod->name = $request->name;
        $paymentMethod->active = boolval($request->active);
        $paymentMethod->save();

        return $paymentMethod;
    }
}