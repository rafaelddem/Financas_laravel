<?php

namespace App\Services\Owner;

use App\Models\Owner;
use Illuminate\Http\Request;

class OwnerService
{
    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  string $message
     * @return \Illuminate\Contracts\View\View
     */
    public function loadPage(int $id, string $message)
    {
        $owner = Owner::find($id);

        $listOwners = Owner::query()
            ->select([
                'id',
                'name',
                'active',
            ])
            ->get();

        return view('owner.index', compact('listOwners', 'owner', 'message'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $input
     * @return Owner
     */
    public function create(array $input)
    {
        $input['active'] = $input['active'] ?? false;
        return Owner::create($input);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  array $input
     * @return Owner
     */
    public function update(array $input)
    {
        $owner = Owner::find($input['id']);
        $activate = $input['active'] ?? false;

        if ($owner->haveAmountsToPayOrReceive())
            throw new \Exception('A atualização do registro não foi permitida pois algumas carteiras desta pessoa ainda possuem valores a quitar ou receber');

        if ($activate) {
            $owner->getMainWallet()->update(['active' => true]);
        } else {
            $owner->wallets->map(function($wallet) {
                $wallet->update(['active' => false]);
            });
        }

        $owner->update(['active' => $activate]);

        return $owner;
    }
}
