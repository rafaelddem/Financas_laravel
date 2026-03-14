<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Transaction\UpdateRequest;
use App\Http\Requests\Transaction\CreateRequest;
use App\Services\PaymentMethodService;
use App\Services\TransactionService;
use App\Services\CategoryService;
use App\Services\TransactionBaseService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    private TransactionService $service;
    private TransactionBaseService $transactionBaseService;
    private CategoryService $categoryService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->service = app(TransactionService::class);
        $this->transactionBaseService = app(TransactionBaseService::class);
        $this->categoryService = app(CategoryService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $transactions = [];

        try {
            $transactions = $this->service->listLastTransactionsCreated();
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
        $categories = [];
        $paymentMethods = [];
        $sourceWallets = [];
        $destinationWallets = [];
        $transactionBases = [];
        $transactionBase = null;

        try {
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();
            $transactionBases = $this->transactionBaseService->list();
            if ($request->has('base')) {
                $transactionBase = $this->transactionBaseService->find($request->get('base'));
            }

            return view('transaction.create', compact('transactionBases', 'transactionBase', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets'));
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

    public function show(int $id)
    {
        try {
            $transaction = $this->service->find($id, ['installments', 'category', 'paymentMethod', 'sourceWallet', 'destinationWallet', 'card']);

            return view('transaction.show', compact('transaction'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'));
    }

    public function edit(int $id)
    {
        try {
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();
            $transactionBases = $this->transactionBaseService->list();
            
            $transaction = $this->service->find($id, ['installments', 'category', 'paymentMethod', 'sourceWallet', 'destinationWallet', 'card']);

            return view('transaction.edit', compact('transaction', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('categories', 'paymentMethods', 'sourceWallets', 'destinationWallets', 'message'));
    }

    public function update(UpdateRequest $request)
    {
        try {
            $this->service->update($request->get('id'), $request->all());

            $message = __('Data updated successfully.');
            return redirect(route('transaction.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }

    public function destroy(Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
            return redirect(route('transaction.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }
}
