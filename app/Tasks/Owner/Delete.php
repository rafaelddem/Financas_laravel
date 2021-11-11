<?php

namespace App\Tasks\Owner;

use App\Models\Owner;

class Delete
{
    public function run(int $id)
    {
        $owner = Owner::find($id);
        $owner->delete($id);
    }
}