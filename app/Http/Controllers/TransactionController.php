<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Transaction\CreateRequest;
use App\Services\PaymentMethodService;
use App\Services\TransactionService;
use App\Services\TransactionTypeService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $service;
    private TransactionTypeService $transactionTypeService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->service = app(TransactionService::class);
        $this->transactionTypeService = app(TransactionTypeService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $transactions = [];

        try {
            $transactions = $this->service->list();
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('transaction.index', compact('transactions', 'message'));
    }

    public function create(Request $request)
    {
        $transactionTypes = [];
        $paymentMethods = [];
        $sourceWallets = [];
        $destinationWallets = [];

        try {
            $transactionTypes = $this->transactionTypeService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();

            return view('transaction.create', compact('transactionTypes', 'paymentMethods', 'sourceWallets', 'destinationWallets'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction.list'))->withErrors(compact('message'));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
            return redirect(route('transaction.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }
}
