<?php

namespace App\Tasks\Owner;

use App\Http\Requests\OwnerRequest;
use App\Models\Owner;

class Update
{
    public function run(OwnerRequest $request)
    {
        $owner = Owner::find($request->id);
        $owner->name = $request->name;
        $owner->active = boolval($request->active);
        $owner->update();

        return $owner;
    }
}