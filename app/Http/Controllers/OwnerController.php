<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Http\Requests\Owner\CreateRequest;
use App\Http\Requests\Owner\UpdateRequest;
use App\Services\OwnerService;
use Illuminate\Http\Request;

class OwnerController extends Controller
{
    private OwnerService $service;

    public function __construct()
    {
        $this->service = app(OwnerService::class);
    }

    public function index(Request $request)
    {
        $owners = [];

        try {
            $owners = $this->service->list(false);
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('owner.index', compact('owners', 'message'));
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

        return redirect(route('owner.list', compact('message')));
    }

    public function update(UpdateRequest $request)
    {
        $message = '';

        try {
            if ($request->get('active')) {
                $this->service->activate($request->get('id'));
            } else {
                $this->service->inactivate($request->get('id'));
            }

            $message = __('Data updated successfully.');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('owner.list', compact('message')));
    }
}
