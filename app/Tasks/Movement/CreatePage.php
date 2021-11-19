<?php

namespace App\Tasks\Movement;

use App\Models\Movement;
use App\Models\MovementType;
use App\Models\Owner;
use Illuminate\Database\Eloquent\Collection;

class CreatePage
{
    public function run(int $id, string $message)
    {
        $movement = new Movement();
        // $movement = Movement::find(12);//($id);
        // $movement->source_wallet = "4";
        // $movement->destination_wallet = "2";

        // registros
        $movements = Movement::query()
            ->select([
                'id',
                'movement_date',
                'net_value',
                'movement_type',
                'title',
            ])
            ->limit(17)
            ->orderBy('id', 'desc')
            ->get();

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

        return view('movement.create', compact('movements', 'movement', 'movementTypes', 'systemWallets', 'personalWallets', 'thirdPartyWallet', 'message'));
    }
}