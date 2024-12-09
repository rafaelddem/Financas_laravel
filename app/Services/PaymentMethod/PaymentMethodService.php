<?php

namespace App\Services\PaymentMethod;

use App\Exceptions\ActivationException;
use App\Exceptions\InactivationException;
use App\Models\PaymentMethod;

class PaymentMethodService
{
    /**
     * Return a blade page to mantain the PaymentMethod.
     *
     * @param  string $message
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function loadPage(int $id, string $message)
    {
        $paymentMethod = PaymentMethod::find($id);

        $paymentMethods = PaymentMethod::query()->get();

        return view('payment-method.index', compact('paymentMethod', 'paymentMethods', 'message'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $input
     * @return Wallet
     */
    public function create(array $input)
    {
        return PaymentMethod::create($input);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @param  array $input
     * @return Wallet
     */
    public function update(int $id, array $input)
    {
        $wallet = PaymentMethod::find($id);

        if (empty($wallet)) {
            throw new \Exception('Payment Method not found');
        }

        $wallet->update($input);

        return $wallet;
    }

    /**
     * Remove the specified resource in storage.
     *
     * @param  int $id
     */
    public function destroy(int $id)
    {
        $wallet = PaymentMethod::find($id);

        if (empty($wallet)) {
            throw new \Exception('Payment Method not found');
        }

        if ($this->verifyDependencies()) {
            throw new \Exception('Payment Method has dependencies');
        }

        $wallet->delete();

        return true;
    }

    // Método tem de ser melhorado após implementação da entidade Transação
    private function verifyDependencies()
    {
        return true;
    }
}
