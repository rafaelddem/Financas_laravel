<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\ExtractModule\CreateRequest;
use App\Services\ExtractModuleService;
use App\Services\TransactionBaseService;
use Illuminate\Http\Request;

class ExtractModuleController extends Controller
{
    private ExtractModuleService $service;
    private TransactionBaseService $transactionBaseService;

    public function __construct()
    {
        $this->service = app(ExtractModuleService::class);
        $this->transactionBaseService = app(TransactionBaseService::class);
    }

    public function index(Request $request)
    {
        $extractModules = [];
        $transactionBases = [];

        try {
            $extractModules = $this->service->list(false);
            $transactionBases = $this->transactionBaseService->list();

            $message = $request->get('message');
            return view('extract-module.index', compact('extractModules', 'transactionBases', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-module.index', compact('extractModules', 'transactionBases', 'message')))->withErrors(compact('message'));
    }

    public function store(CreateRequest $request)
    {
        try {
            $this->service->create($request->all());

            $message = __('Data created successfully.');
            return redirect(route('extract-module.index', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-module.index'))->withErrors(compact('message'))->withInput();
    }

    public function destroy(Request $request)
    {
        try {
            $this->service->delete($request->get('id'));

            $message = __('Data deleted successfully.');
            return redirect(route('extract-module.index', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-module.index'))->withErrors(compact('message'));
    }
}
