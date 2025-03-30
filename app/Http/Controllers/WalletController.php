<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Wallet\CreateRequest;
use App\Http\Requests\Wallet\UpdateRequest;
use App\Repositories\OwnerRepository;
use App\Services\WalletService;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    private WalletService $service;

    public function __construct()
    {
        $this->service = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $wallets = [];

        try {
            $wallets = $this->service->list();
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('wallet.index', compact('wallets', 'message'));
    }

    public function create()
    {
        try {
            $owners = app(OwnerRepository::class)->list();

            return view('wallet.create', compact('owners'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function edit(int $id, Request $request)
    {
        try {
            $wallet = $this->service->find($id);

            return view('wallet.edit', compact('wallet'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function update(UpdateRequest $request)
    {
        try {
            $this->service->update($request->get('id'), $request->only('main_wallet', 'active', 'description'));

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function destroy(Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }
}
