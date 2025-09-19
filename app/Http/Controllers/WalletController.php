<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Wallet\CreateRequest;
use App\Http\Requests\Wallet\UpdateRequest;
use App\Services\OwnerService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private WalletService $service;
    private OwnerService $ownerService;

    public function __construct()
    {
        $this->service = app(WalletService::class);
        $this->ownerService = app(OwnerService::class);
    }

    public function index(int $owner_id, Request $request)
    {
        $wallets = [];

        try {
            $owner = $this->ownerService->find($owner_id, ['wallets']);
            $wallets = $owner->wallets->sortBy([
                ['main_wallet', 'desc'],
                ['active', 'desc'],
                ['name', 'asc'],
            ]);

            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('wallet.index', compact('wallets', 'owner', 'message'));
    }

    public function create(int $owner_id, Request $request)
    {
        try {
            $owner = $this->ownerService->find($owner_id);

            return view('wallet.create', compact('owner'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'));
    }

    public function store(int $owner_id, CreateRequest $request)
    {
        try {
            $this->service->create(array_merge($request->all(), ['owner_id' => $owner_id]));

            $message = __('Data created successfully.');
            return redirect(route('owner.wallet.list', compact('message', 'owner_id')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }

    public function edit(int $owner_id, int $id, Request $request)
    {
        try {
            $wallet = $this->service->find($id, ['owner']);

            return view('wallet.edit', compact('wallet'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.list', compact('owner_id')))->withErrors(compact('message'));
    }

    public function update(int $owner_id, UpdateRequest $request)
    {
        try {
            $this->service->update($request->get('id'), $request->only('main_wallet', 'active', 'description'));

            $message = __('Data updated successfully.');
            return redirect(route('owner.wallet.list', compact('message', 'owner_id')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'));
    }

    public function destroy(int $owner_id, Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
            return redirect(route('owner.wallet.list', compact('message', 'owner_id')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.wallet.list', compact('owner_id')))->withErrors(compact('message'));
    }
}
