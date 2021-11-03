<?php

namespace App\Tasks\Owner;

use App\Models\Owner;

class LoadPage
{
    public function run(int $id, string $message)
    {
        $owner = Owner::find($id);

        $owners = Owner::query()
            ->select([
                'id',
                'name',
                'active',
            ])
            ->get();

        return view('owner.index', compact('owners', 'owner', 'message'));
    }
}