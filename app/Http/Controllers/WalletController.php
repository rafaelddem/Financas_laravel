<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Tasks\Wallet\Delete;
use App\Tasks\Wallet\Insert;
use App\Tasks\Wallet\LoadPage;
use App\Tasks\Wallet\Update;
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
        try {
            (new Insert)->run($request);

            $message = 'Registro criado com sucesso';
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar criar o registro';
        }

        return (new LoadPage)->run(0, $message);
    }

    public function update(WalletRequest $request)
    {
        try {
            (new Update)->run($request);

            $message = 'Registro atualizado com sucesso';
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar atualizar o registro';
        }

        return (new LoadPage)->run(0, $message);
    }

    public function destroy(int $id)
    {
        try {
            (new Delete)->run($id);

            $message = 'Registro excluído com sucesso';
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar excluir o registro';
        }

        return (new LoadPage)->run(0, $message);
    }
}
