<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\PaymentMethod\CreateRequest;
use App\Http\Requests\PaymentMethod\UpdateRequest;
use App\Services\PaymentMethodService;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    private PaymentMethodService $service;

    public function __construct()
    {
        $this->service = app(PaymentMethodService::class);
    }

    public function index(Request $request)
    {
        $paymentMethods = [];

        try {
            $paymentMethods = $this->service->list();
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('payment-method.index', compact('paymentMethods', 'message'));
    }

    public function create()
    {
        return view('payment-method.create');
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

        return redirect(route('payment-method.list', compact('message')));
    }

    public function update(UpdateRequest $request)
    {
        $message = '';

        try {
            $this->service->update($request->get('id'), $request->only(['active']));

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('payment-method.list', compact('message')));
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

        return redirect(route('payment-method.list', compact('message')));
    }
}
