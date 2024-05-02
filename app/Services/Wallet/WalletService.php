<?php

namespace App\Services\Wallet;

use App\Exceptions\ActivationException;
use App\Exceptions\InactivationException;
use App\Models\Owner;
use App\Models\Wallet;

class WalletService
{
    /**
     * Return a blade page to mantain the wallet.
     *
     * @param  string $message
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function loadPage(int $id, string $message)
    {
        $wallet = Wallet::find($id);

        $wallets = Wallet::query()
            ->with('owner')
            ->orderby('owner_id', 'asc')
            ->orderby('main_wallet', 'desc')
            ->orderby('id', 'asc')
            ->get();

        $owners = Owner::query()
            ->where('active', '=', true)
            ->get();

        return view('wallet.index', compact('owners', 'wallet', 'wallets', 'message'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $input
     * @return Wallet
     */
    public function create(array $input)
    {
        $input['active'] = $input['active'] ?? false;
        $wallet = Wallet::create($input);

        self::guaranteesSingleMainWallet($wallet);

        return $wallet;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $input
     * @return Wallet
     */
    public function update(array $input)
    {
        $wallet = Wallet::find($input['id']);

        if (empty($wallet)) {
            throw new \Exception('Wallet not found');
        }

        if ($wallet->main_wallet) {
            unset($input['main_wallet']);
        }

        $input['active'] = $input['active'] ?? false;
        if ($input['active']) {
            self::activationPermitted($wallet);
        } else {
            self::inactivationPermitted($wallet);
        }

        $wallet->update($input);

        self::guaranteesSingleMainWallet($wallet);

        return $wallet;
    }

    /**
     * Update all Wallets by setting the 'main_wallet' attribute to 'false'. Only Wallets with the same owner as the one sent by parameter will be updated. The Wallet sent by parameter will not be updated
     * @param Wallet $wallet
     */
    private function guaranteesSingleMainWallet(Wallet $wallet)
    {
        if ($wallet->main_wallet) {
            Wallet::query()
                ->where('owner_id', $wallet->owner_id)
                ->where('id', '!=', $wallet->id)
                ->update([ 'main_wallet' => false ]);
        }
    }

    /**
     * Checks if the wallet can be activate
     *
     * @param Wallet $wallet
     */
    private function activationPermitted(Wallet $wallet)
    {
        if (!$wallet->owner->active) {
            throw new ActivationException();
        }

        return true;
    }

    /**
     * Checks if the wallet can be inactivate
     *
     * @param Wallet $wallet
     */
    private function inactivationPermitted(Wallet $wallet)
    {
        if ($wallet->main_wallet || $wallet->haveAmountsToPayOrReceive()) {
            throw new InactivationException();
        }

        return true;
    }
}
