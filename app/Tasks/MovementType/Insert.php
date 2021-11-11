<?php

namespace App\Tasks\MovementType;

use App\Http\Requests\MovementTypeRequest;
use App\Models\MovementType;

class Insert
{
    public function run(MovementTypeRequest $request)
    {
        $movementType = new MovementType();
        $movementType->name = $request->name;
        $movementType->relevance = $request->relevance;
        $movementType->active = boolval($request->active);
        $movementType->save();

        return $movementType;
    }
}