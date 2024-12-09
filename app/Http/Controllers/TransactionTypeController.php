<?php

namespace App\Http\Controllers;

use App\Services\TransactionType\TransactionTypeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionTypeController extends Controller
{
    private PaymentMethodService $service;

    public function __contruct()
    {
        $this->service = app(TransactionTypeService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->service->loadPage($request->get('id', 0), "");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->service->create($request->all());

            $message = 'Registro criado com sucesso';
            DB::commit();
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar criar o registro';
            DB::rollBack();
        }

        return $this->service->loadPage(0, $message);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->service->update($request['id'], $request->only('relevance'));

            $message = 'Registro atualizado com sucesso';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao tentar atualizar o registro';
        }

        return $this->service->loadPage(0, $message);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            DB::beginTransaction();

            $this->service->destroy($request['id'], $request->only('relevance'));

            $message = 'Registro removido com sucesso';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao tentar remover o registro';
        }

        return $this->service->loadPage(0, $message);
    }
}
