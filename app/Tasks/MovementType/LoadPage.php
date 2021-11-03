<?php

namespace App\Tasks\MovementType;

use App\Models\MovementType;

class LoadPage
{
    public function run(int $id, string $message)
    {
        $movementType = MovementType::find($id);

        $movementTypes = MovementType::query()
            ->select([
                'id',
                'name',
                'active',
            ])
            ->get();

        return view('movementType.index', compact('movementTypes', 'movementType', 'message'));
    }
}