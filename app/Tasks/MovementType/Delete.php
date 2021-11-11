<?php

namespace App\Tasks\MovementType;

use App\Models\MovementType;

class Delete
{
    public function run(int $id)
    {
        $movementType = MovementType::find($id);
        $movementType->delete($id);
    }
}