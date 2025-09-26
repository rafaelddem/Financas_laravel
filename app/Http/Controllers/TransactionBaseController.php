<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\TransactionBase\CreateRequest;
use App\Services\CategoryService;
use App\Services\PaymentMethodService;
use App\Services\TransactionBaseService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class TransactionBaseController extends Controller
{
    private TransactionBaseService $service;
    private CategoryService $categoryService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->service = app(TransactionBaseService::class);
        $this->categoryService = app(CategoryService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $transactionBases = [];

        try {
            $transactionBases = $this->service->list();
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('transaction-base.index', compact('transactionBases', 'message'));
    }

    public function create(Request $request)
    {
        $categories = [];
        $paymentMethods = [];
        $sourceWallets = [];
        $destinationWallets = [];

        try {
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();

            return view('transaction-base.create', compact('categories', 'paymentMethods', 'sourceWallets', 'destinationWallets'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-base.list'))->withErrors(compact('message'));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
            return redirect(route('transaction-base.list', compact('message')));
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
            return redirect(route('transaction-base.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('transaction-base.list'))->withErrors(compact('message'));
    }
}
