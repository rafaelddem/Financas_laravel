<?php

namespace App\Tasks\PaymentMethod;

use App\Models\PaymentMethod;

class LoadPage
{
    public function run(int $id, string $message)
    {
        $paymentMethod = PaymentMethod::find($id);

        $paymentMethods = PaymentMethod::query()
            ->select([
                'id',
                'name',
                'active',
            ])
            ->get();

        return view('paymentMethod.index', compact('paymentMethods', 'paymentMethod', 'message'));
    }
}