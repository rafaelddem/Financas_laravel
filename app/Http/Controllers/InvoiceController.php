<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Exceptions\BaseException;
use App\Services\InvoiceService;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private InvoiceService $service;

    public function __construct()
    {
        $this->service = app(InvoiceService::class);
    }

    public function index(Request $request)
    {
        $openInvoices = [];
        $closedInvoices = [];
        $overdueInvoices = [];
        $paidInvoices = [];

        try {
            $openInvoices = $this->service->listInvoices(InvoiceStatus::Open);
            $paidInvoices = $this->service->listInvoices(InvoiceStatus::Paid);
            $closedInvoices = $this->service->listInvoices(InvoiceStatus::Closed);
            $overdueInvoices = $this->service->listInvoices(InvoiceStatus::Overdue);
            $closedInvoices = $closedInvoices->merge($overdueInvoices)->sortByDesc('status');
            $message = $request->get('message');
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('invoice.index', compact('openInvoices', 'closedInvoices', 'paidInvoices', 'message'));
    }

    public function pay(Request $request)
    {
        $message = '';

        try {
            $this->service->pay($request->id);

            $message = __('Action executed successfully.');
            return redirect(route('invoice.list', compact('message')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('invoice.list'))->withErrors(compact('message'))->withInput();
    }
}
