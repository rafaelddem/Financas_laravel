<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Services\ExtractImport\NuBankCSVExtrator;
use App\Services\ExtractModuleService;
use Illuminate\Http\Request;

class ExtractImportController extends Controller
{
    private NuBankCSVExtrator $serviceExtractor;
    private ExtractModuleService $extractModuleService;

    public function __construct()
    {
        $this->serviceExtractor = app(NuBankCSVExtrator::class);
        $this->extractModuleService = app(ExtractModuleService::class);
    }

    public function index(Request $request)
    {
        $importOptions = [];

        try {
            $importOptions = $this->extractModuleService->list();

            $message = $request->get('message');
            return view('extract-import.index', compact('importOptions', 'message'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('extract-import.index', compact('importOptions')))->withErrors(compact('message'));
    }

    public function extract(Request $request)
    {
        $importOptions = [];

        try {
            $this->serviceExtractor->process($request->module_id, $request->file('extract_file'));

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
