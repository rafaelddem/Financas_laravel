<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementRequest;
use App\Tasks\Movement\Insert;
use App\Tasks\Movement\CreatePage;
use App\Tasks\Movement\ListPage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovementController extends Controller
{
    public function index(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new ListPage)->run($id, "");
    }

    public function create(Request $request)
    {
        $id = isset($request->id) ? $request->id : 0;
        return (new CreatePage)->run($id, "");
    }

    public function store(MovementRequest $request)
    {
        try {
            (new Insert)->run($request);

            $message = 'Registro salvo com sucesso';
        } catch (\Throwable $th) {
            DB::rollBack();
            $message = 'Erro ao salvar o movimento';
        }

        return (new CreatePage)->run(0, $message);
    }
}
