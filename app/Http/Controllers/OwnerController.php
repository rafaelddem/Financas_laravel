<?php

namespace App\Http\Controllers;

use App\Http\Requests\OwnerRequest;
use Illuminate\Http\Request;
use App\Tasks\Owner\Delete;
use App\Tasks\Owner\Insert;
use App\Tasks\Owner\LoadPage;
use App\Tasks\Owner\Update;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $codigoOwner = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($codigoOwner, "");
    }

    public function store(OwnerRequest $request)
    {
        try {
            (new Insert)->run($request);

            $message = 'Registro criado com sucesso';
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar criar o registro';
        }

        return (new LoadPage)->run(0, $message);
    }

    public function update(OwnerRequest $request)
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
