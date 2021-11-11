<?php

namespace App\Tasks\Movement;

use App\Http\Requests\InstallmentRequest;
use App\Http\Requests\MovementRequest;
use App\Models\Movement;
use App\Tasks\Financial\Money;
use App\Tasks\Installment\Insert as InstallmentInsert;
use Illuminate\Support\Facades\DB;

class Insert
{
    public function run(MovementRequest $request)
    {
        DB::beginTransaction();

        $movement = new Movement();

        $movement->title = $request->title;
        $movement->installments = $request->installments;
        $movement->movement_date = $request->movement_date;
        $movement->movement_type = $request->movement_type;
        $movement->gross_value = (new Money)->extractValue($request->gross_value);
        $movement->descount_value = (new Money)->extractValue($request->descount_value);
        $movement->rounding_value = (new Money)->extractValue($request->rounding_value);
        $movement->net_value = (new Money)->extractValue($request->net_value);
        $movement->relevance = $request->relevance;
        $movement->description = $request->description;
        $movement->save();

        for ($installment = 0; $installment < $request->installments; $installment++) {
            $installmentData = $request->only([
                'descount_value', 
                'rounding_value', 
                'net_value', 
                'source_wallet', 
                'destination_wallet', 
            ]);

            $installmentData['movement'] = $movement->id;
            $installmentData['installment_number'] = $installment + 1;
            $installmentData['duo_date'] = $request->installment_duo_date[$installment];
            $installmentData['gross_value'] = $request->installment_gross_value[$installment];

            $installmentRequest = InstallmentRequest::create($request->getUri(), 'GET', $installmentData);
            (new InstallmentInsert)->run($installmentRequest);
        }

        DB::commit();
    }
}