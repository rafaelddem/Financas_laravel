<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;

class ConfigController extends Controller
{
    private ConfigurationService $service;

    public function __construct()
    {
        $this->service = app(ConfigurationService::class);
    }

    public function index(Request $request)
    {
        $configurations = [];

        try {
            $configurations = $this->service->list(false);
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('configuration.index', compact('configurations', 'message'));
    }

    public function update(Request $request)
    {
        $configurations = [];
        try {
            $this->service->updateConfigurations($request->except(['_token', '_method']));

            $configurations = $this->service->list(false);

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('configuration.index', compact('configurations', 'message'));
    }

    public function backup(Request $request)
    {
        try {
            return $this->service->backupDownload();

            $message = __('Backup created successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('configuration.index', compact('message')));
    }

    public function restore(Request $request)
    {
        try {
            $this->service->restore($request->file('backup_file'));

            $message = __('Backup restored successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('configuration.index', compact('message')));
    }
}
