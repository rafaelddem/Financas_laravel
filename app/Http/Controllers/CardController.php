<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Card\CreateRequest;
use App\Http\Requests\Card\UpdateRequest;
use App\Services\CardService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class CardController extends Controller
{
    private CardService $service;
    private WalletService $walletService;

    public function __construct()
    {
        $this->service = app(CardService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(int $owner_id, int $wallet_id, Request $request)
    {
        $cards = [];

        try {
            $wallet = $this->walletService->find($wallet_id, ['cards', 'owner']);
            $cards = $wallet->cards->sortBy([
                ['active', 'desc'],
                ['name', 'asc'],
            ]);

            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('card.index', compact('wallet', 'cards', 'message'));
    }

    public function listDebit(int $owner_id, int $wallet_id)
    {
        try {
            return $this->service->listDebit($wallet_id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function listCredit(int $owner_id, int $wallet_id)
    {
        try {
            return $this->service->listCredit($wallet_id);
        } catch (\Throwable $th) {
            return [];
        }
    }

    public function create(int $owner_id, int $wallet_id)
    {
        try {
            $wallet = $this->walletService->find($wallet_id, ['owner']);

            return view('card.create', compact('wallet'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.card.list', compact('owner_id', 'wallet_id', 'message')));
    }

    public function store(int $owner_id, int $wallet_id, CreateRequest $request)
    {
        try {
            $this->service->create(array_merge($request->all(), ['wallet_id' => $wallet_id]));

            $message = __('Data created successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.card.list', compact('owner_id', 'wallet_id', 'message')));
    }

    public function edit(int $owner_id, int $wallet_id, int $id)
    {
        try {
            $card = $this->service->find($id, ['wallet.owner']);

            return view('card.edit', compact('card'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.card.list', compact('message', 'owner_id', 'wallet_id')));
    }

    public function update(int $owner_id, int $wallet_id, UpdateRequest $request)
    {
        try {
            $this->service->update($request->get('id'), $request->only('first_day_month', 'days_to_expiration', 'active'));

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.card.list', compact('message', 'owner_id', 'wallet_id')));
    }
}
