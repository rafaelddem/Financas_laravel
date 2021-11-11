<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Models\Movement;
use App\Tasks\Movement\Insert;
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
            (new Insert)->run($request);

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
}
