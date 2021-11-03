<?php

namespace App\Http\Controllers;

use App\Http\Requests\OwnerRequest;
use Illuminate\Http\Request;
use App\Models\Owner;
use App\Tasks\Owner\LoadPage;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $codigoOwner = isset($request->id) ? $request->id : 0;
        return (new LoadPage)->run($codigoOwner, "");
    }

    public function store(OwnerRequest $request)
    {
        $owner = new Owner();
        $owner->name = $request->name;
        $owner->active = boolval($request->active);
        $owner->save();

        $message = 'Registro criado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function update(OwnerRequest $request)
    {
        $owner = Owner::find($request->id);
        $owner->name = $request->name;
        $owner->active = boolval($request->active);
        $owner->update();

        $message = 'Registro atualizado com sucesso';

        return (new LoadPage)->run(0, $message);
    }

    public function destroy(int $id)
    {
        $owner = Owner::find($id);
        $owner->delete($id);

        $message = 'Registro excluído com sucesso';

        return (new LoadPage)->run(0, $message);
    }
}
