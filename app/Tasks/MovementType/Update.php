<?php

namespace App\Tasks\MovementType;

use App\Http\Requests\MovementTypeRequest;
use App\Models\MovementType;

class Update
{
    public function run(MovementTypeRequest $request)
    {
        $movementType = MovementType::find($request->id);
        $movementType->name = $request->name;
        $movementType->relevance = $request->relevance;
        $movementType->active = boolval($request->active);
        $movementType->update();

        return $movementType;
    }
}