<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\ExtractImport\UpdateRequest;
use App\Services\CategoryService;
use App\Services\ExtractImport\NuBankCSVExtrator;
use App\Services\ExtractModuleService;
use App\Services\PaymentMethodService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class ExtractImportController extends Controller
{
    private ExtractModuleService $extractModuleService;
    private NuBankCSVExtrator $serviceExtractor;
    private CategoryService $categoryService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->extractModuleService = app(ExtractModuleService::class);
        $this->serviceExtractor = app(NuBankCSVExtrator::class);
        $this->categoryService = app(CategoryService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $filesToImport = [];
        $importOptions = [];
        $importTransactions = [];
        $categories = [];
        $paymentMethods = [];
        $sourceWallets = [];
        $destinationWallets = [];

        try {
            $filesToImport = $this->serviceExtractor->listReadyFiles();
            $importOptions = $this->extractModuleService->list();
            $importTransactions = $this->serviceExtractor->listExtractImportAsTransaction();
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();

            $message = $request->get('message');
            return view('extract-import.index', compact('filesToImport', 'importOptions', 'importTransactions', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index', compact('filesToImport', 'importOptions', 'importTransactions', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets')))->withErrors(compact('message'));
    }

    public function extract(Request $request)
    {
        $importOptions = [];

        try {
            $this->serviceExtractor->extract($request->file('extract_file'), $request->get('module_id'));

            $message = __('The extract data was entered successfully.');
            return redirect(route('extract-import.index', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }

    public function ready(UpdateRequest $request)
    {
        try {
            $this->serviceExtractor->update($request->get('id'), $request->all());

            $message = __('The extract data was entered successfully.');
            return redirect(route('extract-import.index', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }

    public function import(Request $request)
    {
        try {
            $this->serviceExtractor->import($request->get('file_name'));

            $message = __('The extract data was entered successfully.');
            return redirect(route('extract-import.index', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }
}
