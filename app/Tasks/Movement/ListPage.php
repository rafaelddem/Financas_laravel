<?php

namespace App\Tasks\Movement;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Collection;

class ListPage
{
    public function run(int $id, string $message)
    {
        $movement = null;//Movement::find($id);

        // registros
        $movements = Movement::query()
            ->select([
                'id',
                'movement_date',
                'net_value',
                'movement_type',
                'title',
            ])
            ->orderBy('id', 'desc')
            ->paginate(50);

        // combobox
        $movementTypes = MovementType::query()
            ->select([
                'id',
                'name',
            ])
            ->where('active', '=', true)
            ->get();
        $owners = Owner::query()
            ->where('active', '=', true)
            ->get();
        $systemWallets = [];
        $personalWallets = [];
        $thirdPartyWallet = new Collection();
        foreach ($owners as $owner) {
            switch ($owner->referentOwner()) {
                case 0:
                    $systemWallets = $owner->wallets;
                    break;
                case 1:
                    $personalWallets = $owner->wallets;
                    break;
                default:
                    $thirdPartyWallet->add($owner->mainWallet());
                    break;
            }
        }

        return view('movement.index', compact('movements', 'movement', 'movementTypes', 'systemWallets', 'personalWallets', 'thirdPartyWallet', 'message'));
    }
}