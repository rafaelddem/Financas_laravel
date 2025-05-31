<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\TransactionType\CreateRequest;
use App\Http\Requests\TransactionType\UpdateRequest;
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

        return redirect(route('transaction-type.list', compact('message')));
    }

    public function edit(int $id, Request $request)
    {
        $message = '';

        try {
            $transactionType = $this->service->find($id);

            return view('transaction-type.edit', compact('transactionType'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-type.list', compact('message')));
    }

    public function update(UpdateRequest $request)
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
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-type.list', compact('message')));
    }

}
