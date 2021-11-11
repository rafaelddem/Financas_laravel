<?php

namespace App\Tasks\Wallet;

use App\Http\Requests\WalletRequest;
use App\Models\Wallet;

class Insert
{
    public function run(WalletRequest $request)
    {
        $wallet = new Wallet();
        $wallet->name = $request->name;
        $wallet->owner = $request->owner;
        $wallet->active = boolval($request->active);

        if ($wallet->active) {
            $wallet->main_wallet = boolval($request->main_wallet);
        } else {
            $wallet->main_wallet = false;
        }

        $wallet->save();

        return $wallet;
    }
}