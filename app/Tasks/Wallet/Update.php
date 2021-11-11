<?php

namespace App\Tasks\Wallet;

use App\Http\Requests\WalletRequest;
use App\Models\Wallet;

class Update
{
    public function run(WalletRequest $request)
    {
        $wallet = Wallet::find($request->id);
        $wallet->name = $request->name;
        $wallet->owner = $request->owner;
        $wallet->active = boolval($request->active);

        if ($wallet->active) {
            $wallet->main_wallet = boolval($request->main_wallet);
        } else {
            $wallet->main_wallet = false;
        }

        $wallet->update();

        return $wallet;
    }
}