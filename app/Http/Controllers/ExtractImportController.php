<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\ExtractImport\ExtractRequest;
use App\Http\Requests\ExtractImport\ReadyRequest;
use App\Services\CategoryService;
use App\Services\ExtractorService;
use App\Services\PaymentMethodService;
use App\Services\TransactionBaseService;
use App\Services\WalletService;
use Illuminate\Http\Request;

class ExtractImportController extends Controller
{
    private ExtractorService $extractorService;
    private TransactionBaseService $transactionBaseService;
    private CategoryService $categoryService;
    private PaymentMethodService $paymentMethodService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->extractorService = app(ExtractorService::class);
        $this->transactionBaseService = app(TransactionBaseService::class);
        $this->categoryService = app(CategoryService::class);
        $this->paymentMethodService = app(PaymentMethodService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request)
    {
        $modules = [];
        $transactionBases = [];
        $filesToImport = [];
        $importTransactions = [];
        $categories = [];
        $paymentMethods = [];
        $sourceWallets = [];
        $destinationWallets = [];

        try {
            $modules = $this->extractorService->getModules();
            $transactionBases = $this->transactionBaseService->list();
            $filesToImport = $this->extractorService->listReadyFiles();
            $importTransactions = $this->extractorService->listExtractImport();
            $categories = $this->categoryService->list();
            $paymentMethods = $this->paymentMethodService->list();
            $sourceWallets = $destinationWallets = $this->walletService->list();

            $message = $request->get('message');
            return view('extract-import.index', compact('modules', 'transactionBases', 'filesToImport', 'importTransactions', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index', compact('modules', 'filesToImport', 'importTransactions', 'categories', 'paymentMethods', 'sourceWallets', 'destinationWallets')))->withErrors(compact('message'));
    }

    public function extract(ExtractRequest $request)
    {
        try {
            $this->extractorService
                ->configure($request->get('module_id'), $request->get('transaction_base_id_in'), $request->get('transaction_base_id_out'))
                ->extract($request->file('extract_file'));

            return redirect(route('extract-import.index', [
                'message' => __('The extract data was entered successfully.'),
            ]));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }

    public function ready(ReadyRequest $request)
    {
        try {
            $this->extractorService->update($request->get('id'), $request->only(
                'title',
                'category_id',
                'relevance',
                'payment_method_id',
                'source_wallet_id',
                'destination_wallet_id',
                'gross_value',
                'installments',
                'installment_total',
                'installment_number',
            ));

            return redirect(route('extract-import.index', [
                'message' => __('The extract data was entered successfully.'),
            ]));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }

    public function destroy(Request $request)
    {
        try {
            $this->extractorService->delete($request->get('id'));

            return redirect(route('extract-import.index', [
                'message' => __('The extract data was removed successfully.'),
            ]));
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
            $this->extractorService->import($request->get('file_name'));

            return redirect(route('extract-import.index', [
                'message' => __('The extract data was entered successfully.'),
            ]));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index'))->withErrors(compact('message'));
    }
}
