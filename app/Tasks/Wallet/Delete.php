<?php

namespace App\Tasks\Wallet;

use App\Models\Wallet;

class Delete
{
    public function run(int $id)
    {
        $wallet = Wallet::find($id);
        $wallet->delete($id);
    }
}