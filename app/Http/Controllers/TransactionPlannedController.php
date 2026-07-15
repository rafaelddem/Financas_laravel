<?php

namespace App\Http\Controllers;

use App\Enums\Period;
use App\Exceptions\BaseException;
use App\Http\Requests\TransactionPlanned\CreateRequest;
use App\Services\CategoryService;
use App\Services\PaymentMethodService;
use App\Services\TransactionBaseService;
use App\Services\TransactionPlannedService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionPlannedController extends Controller
{
    private TransactionPlannedService $service;
    private TransactionBaseService $transactionBaseService;
    private CategoryService $categoryService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        parent::__construct();

        $this->service = app(TransactionPlannedService::class);
        $this->transactionBaseService = app(TransactionBaseService::class);
        $this->categoryService = app(CategoryService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        try {
            $transactions = $this->service->listLastTransactionsPlannedCreated();
            $message = $request->get('message');
            return view('transaction-planned.index', ['top_bar_data' => $this->top_bar_data] + compact('transactions', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function create(Request $request)
    {
        try {
            $frequency = Period::frequencyValues();
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();
            $transactionBases = $this->transactionBaseService->list();
            $transactionBase = ($request->has('base')) ? $this->transactionBaseService->find($request->get('base')) : null;

            return view('transaction-planned.create', ['top_bar_data' => $this->top_bar_data] + compact('frequency', 'transactionBases', 'transactionBase', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-planned.list'))->withErrors(compact('message'));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
            return redirect(route('transaction-planned.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }

    public function show(int $transactionPlannedId)
    {
        try {
            $transactionsPlanned = $this->service->findByTransactionPlannedId($transactionPlannedId);
            $transactionPlanned = $transactionsPlanned->first();
            $totalTransactionPlanned = $transactionsPlanned->count();

            return view('transaction-planned.show', ['top_bar_data' => $this->top_bar_data] + compact('transactionPlanned', 'totalTransactionPlanned'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'));
    }

    public function approve(int $id)
    {
        try {
            $this->service->approveTransactionPlanned($id);

            $message = __('Data updated successfully.');
            return redirect(route('transaction-planned.list', compact('message')));
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
            $this->service->delete($request->get('id'), $request->get('transaction_planned_id'));

            $message = __('Data deleted successfully.');
            return redirect(route('transaction-planned.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect()->back()->withErrors(compact('message'))->withInput();
    }
}
