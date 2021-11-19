<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Owner;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    public function index(Request $request)
    {
        $installment = Installment::query()
            ->where('movement', '=', $request->movement)
            ->where('installment_number', '=', $request->installment_number)
            ->first();

        $installments = Installment::query()
            ->whereNull('payment_date')
            ->orderBy('duo_date', 'asc')
            ->orderBy('movement', 'asc')
            ->orderBy('installment_number', 'asc')
            ->get();

        $paymentMethods = PaymentMethod::query()
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

        return view('movement.installment', compact('installments', 'installment', 'paymentMethods', 'systemWallets', 'personalWallets', 'thirdPartyWallet'));
    }
}
