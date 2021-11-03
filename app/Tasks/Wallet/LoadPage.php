<?php

namespace App\Tasks\Wallet;

use App\Models\Owner;
use App\Models\Wallet;

class LoadPage
{
    public function run(int $id, string $message)
    {
        $wallet = Wallet::find($id);

        $wallets = Wallet::query()
            ->select([
                'id',
                'name',
                'owner',
                'active',
            ])
            ->orderby('owner', 'asc')
            ->orderby('main_wallet', 'desc')
            ->orderby('id', 'asc')
            ->get();

        $owners = Owner::query()
            ->select([
                'id',
                'name',
            ])
            ->where('active', '=', true)
            ->get();

        return view('wallet.index', compact('owners', 'wallet', 'wallets', 'message'));
    }
}