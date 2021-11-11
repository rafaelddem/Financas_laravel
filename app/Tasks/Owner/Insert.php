<?php

namespace App\Tasks\Owner;

use App\Http\Requests\OwnerRequest;
use App\Models\Owner;

class Insert
{
    public function run(OwnerRequest $request)
    {
        $owner = new Owner();
        $owner->name = $request->name;
        $owner->active = boolval($request->active);
        $owner->save();

        return $owner;
    }
}