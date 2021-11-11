<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementTypeRequest;
use App\Tasks\MovementType\Delete;
use App\Tasks\MovementType\Insert;
use App\Tasks\MovementType\LoadPage;
use App\Tasks\MovementType\Update;
use Illuminate\Http\Request;

class MovementTypeController extends Controller
{
    public function index(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($id, "");
    }

    public function store(MovementTypeRequest $request)
    {
        try {
            (new Insert)->run($request);

            $message = 'Registro criado com sucesso';
        } catch (\Throwable $th) {
            $message = 'Erro ao tentar criar o registro';
        }

        return (new LoadPage)->run(0, $message);
    }

    public function update(MovementTypeRequest $request)
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
