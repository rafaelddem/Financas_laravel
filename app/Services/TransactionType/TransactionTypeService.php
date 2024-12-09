<?php

namespace App\Services\TransactionType;

use App\Exceptions\ActivationException;
use App\Exceptions\InactivationException;
use App\Models\TransactionType;

class TransactionTypeService
{
    /**
     * Return a blade page to mantain the TransactionType.
     *
     * @param  string $message
     * @param  int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function loadPage(int $id, string $message)
    {
        $transactionType = TransactionType::find($id);

        $transactionTypes = TransactionType::query()->get();

        return view('transaction-type.index', compact('transactionType', 'transactionTypes', 'message'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  array $input
     * @return Wallet
     */
    public function create(array $input)
    {
        return TransactionType::create($input);
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
        $wallet = TransactionType::find($id);

        if (empty($wallet)) {
            throw new \Exception('Transaction Type not found');
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
        $wallet = TransactionType::find($id);

        if (empty($wallet)) {
            throw new \Exception('Transaction Type not found');
        }

        if ($this->verifyDependencies()) {
            throw new \Exception('Transaction Type has dependencies');
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
