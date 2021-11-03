<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Models\Installment;
use App\Models\Movement;
use App\Models\MovementType;
use App\Tasks\Movement\LoadPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($id, "");
    }

    public function store(MovementRequest $request)
    {
        try {
            DB::beginTransaction();

            $movement = new Movement();

            // if (isset($request->title)) {
                $movement->title = $request->title;
            // } else {
            //     $tipo = MovementType::query()
            //         ->where('id', '=', $request->movement_type)
            //         ->first('name');
            //     $movement->title = $tipo->name;
            // }

            $movement->installments = $request->installments;
            $movement->movement_date = $request->movement_date;
            $movement->movement_type = $request->movement_type;
            $movement->gross_value = $this->formatValue($request->gross_value);
            $movement->descount_value = $this->formatValue($request->descount_value);
            $movement->rounding_value = $this->formatValue($request->rounding_value);
            $movement->net_value = $this->formatValue($request->net_value);
            $movement->relevance = $request->relevance;
            $movement->description = $request->description;
            $movement->save();

            for ($installment = 0; $installment < $request->installments; $installment++) { 
                $duoDate = $request->installment_duo_date[$installment]; 
                $grossValue = $this->formatValue($request->installment_gross_value[$installment])."";

                $newInstallment = new Installment();
                $newInstallment->movement = $movement->id;
                $newInstallment->installment_number = $installment + 1;
                $newInstallment->duo_date = $duoDate;
                // $newInstallment->payment_date = $request->payment_date;
                $newInstallment->gross_value = $grossValue;
                $newInstallment->descount_value = 0;
                $newInstallment->interest_value = 0;
                $newInstallment->rounding_value = 0;
                $newInstallment->net_value = $grossValue;
                // $newInstallment->payment_method = $request->payment_method;
                $newInstallment->source_wallet = $request->source_wallet;
                $newInstallment->destination_wallet = $request->destination_wallet;
                $newInstallment->save();
            }

            DB::commit();
            $message = 'Registro salvo com sucesso';
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao salvar o movimento|'.$th->getMessage();
        }

        return (new LoadPage)->run(0, $message);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function show(Movement $movement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function edit(Movement $movement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movement  $movement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movement $movement)
    {
        //
    }

    public function destroy(Movement $movement)
    {
        //
    }

    private function formatValue(string $originalValue)
    {
        $firstPosition = substr($originalValue, 0, 1);

        $minus = ($firstPosition == '-') ? '-' : '';

        return $minus . preg_replace('/[^0-9]/', '', $originalValue) / 100;
    }
}
