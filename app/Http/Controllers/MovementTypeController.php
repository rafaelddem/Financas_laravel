<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovementTypeRequest;
use App\Models\MovementType;
use App\Tasks\MovementType\LoadPage;
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
        $movementType = new MovementType();
        $movementType->name = $request->name;
        $movementType->relevance = $request->relevance;
        $movementType->active = boolval($request->active);
        $movementType->save();

        $message = 'Registro criado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function update(MovementTypeRequest $request)
    {
        $movementType = MovementType::find($request->id);
        $movementType->name = $request->name;
        $movementType->relevance = $request->relevance;
        $movementType->active = boolval($request->active);
        $movementType->update();

        $message = 'Registro atualizado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function destroy(int $id)
    {
        $movementType = MovementType::find($id);
        $movementType->delete($id);

        $message = 'Registro excluído com sucesso';

        return (new LoadPage)->run(0, $message);
    }
}
