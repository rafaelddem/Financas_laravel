<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Models\Wallet;
use App\Tasks\Wallet\LoadPage;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($id, "");
    }

    public function store(WalletRequest $request)
    {
        $wallet = new Wallet();
        $wallet->name = $request->name;
        $wallet->owner = $request->owner;
        $wallet->active = boolval($request->active);

        if ($wallet->active) {
            $wallet->main_wallet = boolval($request->main_wallet);
        } else {
            $wallet->main_wallet = false;
        }

        $wallet->save();

        $mensagem = 'Registro criado com sucesso';

        return (new LoadPage)->run(0, $mensagem);
    }

    public function update(WalletRequest $request)
    {
        $wallet = Wallet::find($request->id);
        $wallet->name = $request->name;
        $wallet->owner = $request->owner;
        $wallet->active = boolval($request->active);

        if ($wallet->active) {
            $wallet->main_wallet = boolval($request->main_wallet);
        } else {
            $wallet->main_wallet = false;
        }

        $wallet->update();

        $mensagem = 'Registro atualizado com sucesso';

        return (new LoadPage)->run(0, $mensagem);
    }

    public function destroy(int $id)
    {
        $wallet = Wallet::find($id);
        $wallet->delete($id);

        $mensagem = 'Registro excluído com sucesso';

        return (new LoadPage)->run(0, $mensagem);
    }
}
