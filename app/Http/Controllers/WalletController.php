<?php

namespace App\Http\Controllers;

use \DB;
use App\Exceptions\ActivationException;
use App\Exceptions\InactivationException;
use App\Http\Requests\WalletRequest;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return app(WalletService::class)->loadPage($request->get('id', 0), "");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(WalletRequest $request)
    {
        try {
            DB::beginTransaction();

            app(WalletService::class)->create($request->only('name', 'owner_id', 'main_wallet', 'active', 'description'));

            $message = 'Registro criado com sucesso';
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao tentar criar o registro';
        }

        return app(WalletService::class)->loadPage(0, $message);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return app(WalletService::class)->loadPage($request->get('id', $id), "");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();

            app(WalletService::class)->update($request->only('id', 'main_wallet', 'active', 'description'));

            $message = 'Registro atualizado com sucesso';
            DB::commit();
        } catch (ActivationException $inactivationException) {
            DB::rollBack();
            $message = 'Ativação de registro não permitida';
        } catch (InactivationException $inactivationException) {
            DB::rollBack();
            $message = 'Inativação de registro não permitida';
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao tentar atualizar o registro';
        }

        return app(WalletService::class)->loadPage(0, $message);
    }
}
