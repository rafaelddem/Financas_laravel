<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use App\Models\Owner;
use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InstallmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function show(Installment $installment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function edit(Installment $installment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Installment $installment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Installment  $installment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Installment $installment)
    {
        //
    }
}
