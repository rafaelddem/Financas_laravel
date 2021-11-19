<?php

namespace App\Tasks\Installment;

use App\Http\Requests\InstallmentRequest;
use App\Models\Installment;
use App\Tasks\Financial\Money;

class Insert
{
    public function run(InstallmentRequest $request)
    {
        // DB::beginTransaction();

        $installment = new Installment();
        $installment->movement = $request->movement;
        $installment->installment_number = $request->installment_number;
        $installment->duo_date = $request->duo_date;
        $installment->payment_date = $request->payment_date;
        $installment->gross_value = (new Money($request->gross_value))->getValue();
        $installment->descount_value = (new Money($request->descount_value))->getValue();
        $installment->interest_value = (new Money($request->interest_value))->getValue();
        $installment->rounding_value = (new Money($request->rounding_value))->getValue();
        $installment->net_value = (new Money($request->net_value))->getValue();
        $installment->payment_method = $request->payment_method;
        $installment->source_wallet = $request->source_wallet;
        $installment->destination_wallet = $request->destination_wallet;
        $installment->save();

        // DB::commit();
    }
}