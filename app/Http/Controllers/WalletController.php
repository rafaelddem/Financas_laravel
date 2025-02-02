<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\WalletRequest;
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

        return view('wallet.index', compact('message'));
    }

    public function store(WalletRequest $request)
    {
        try {
            /**
             * Regra a seguir pode ser contornada com uma validação no Request
             * Entretanto a validação não está funcionando, e não consegui identificar o motivo
             * Uma vez corrigido, remover a linha a seguir
             */
            $request->merge([ 'active' => $request->get('main_wallet') ? true : $request->get('active') ]);

            $this->service->create($request->all());

            $message = __('Data created successfully');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function edit(Request $request)
    {
        try {
            $wallet = $this->service->find($request->get('id'));

            return view('wallet.edit', compact('wallet'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('wallet.index', compact('message'));
    }

    public function update(WalletRequest $request)
    {
        try {
            /**
             * Regra a seguir pode ser contornada com uma validação no Request
             * Entretanto a validação não está funcionando, e não consegui identificar o motivo
             * Uma vez corrigido, remover a linha a seguir
             */
            $request->merge([ 'active' => $request->get('main_wallet') ? true : $request->get('active') ]);

            $this->service->update($request->get('id'), $request->all());

            $message = __('Data updated successfully');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('wallet.list', compact('message')));
    }

    public function destroy(Request $request)
    {
        // $message = __('Data deleted successfully');
        $message = __('Função ainda não implementada');
        return redirect(route('wallet.list', compact('message')));
    }
}
