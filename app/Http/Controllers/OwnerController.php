<?php

namespace App\Http\Controllers;

use App\Http\Requests\OwnerRequest;
use App\Models\Owner;
use App\Models\Wallet;
use App\Services\Owner\OwnerService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return app(OwnerService::class)->loadPage($request->get('id', 0), "");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(OwnerRequest $request)
    {
        try {
            DB::beginTransaction();

            $owner = app(OwnerService::class)->create($request->all());

            $wallet = app(WalletService::class)->create([
                'name' => 'Carteira de ' . $owner->name,
                'owner_id' => $owner->id,
                'main_wallet' => true,
                'active' => true,
                'description' => 'Carteira padrão de ' . $owner->name,
            ]);

            $message = 'Registro criado com sucesso';
            DB::commit();
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar criar o registro';
            DB::rollBack();
        }

        return app(OwnerService::class)->loadPage(0, $message);
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

            app(OwnerService::class)->update($request->only(['active', 'id']));

            $message = 'Registro atualizado com sucesso';
            DB::commit();
        } catch (\Exception $ex) {
            $message = $ex->getMessage();
            DB::rollBack();
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar atualizar o registro';
            DB::rollBack();
        }

        return app(OwnerService::class)->loadPage(0, $message);
    }
}
