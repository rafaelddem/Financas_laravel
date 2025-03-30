<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\TransactionTypeRequest;
use App\Services\TransactionTypeService;
use Illuminate\Http\Request;

class TransactionTypeController extends Controller
{
    private TransactionTypeService $service;

    public function __construct()
    {
        $this->service = app(TransactionTypeService::class);
    }

    public function index(Request $request)
    {
        $transactionTypes = [];

        try {
            $transactionTypes = $this->service->list();
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('transaction-type.index', compact('transactionTypes', 'message'));
    }

    public function create()
    {
        return view('transaction-type.create');
    }

    public function store(TransactionTypeRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-type.list', compact('message')));
    }

    public function edit(Request $request)
    {
        $message = '';

        try {
            $transactionType = $this->service->find($request->get('id'));

            return view('transaction-type.edit', compact('transactionType'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-type.list', compact('message')));
    }

    public function update(TransactionTypeRequest $request)
    {
        $message = '';

        try {
            $transactionType = $this->service->update($request->get('id'), $request->only([
                'relevance', 'active'
            ]));

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-type.list', compact('message')));
    }

    public function destroy(Request $request)
    {
        // $message = __('Data deleted successfully.');
        $message = __('Função ainda não implementada');
        return redirect(route('transaction-type.list', compact('message')));
    }

}
