<?php

namespace App\Http\Controllers;

use App\Exceptions\BaseException;
use App\Services\OwnerService;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private ReportService $service;
    private OwnerService $ownerService;

    public function __construct()
    {
        $this->service = app(ReportService::class);
        $this->ownerService = app(OwnerService::class);
    }

    public function index(Request $request)
    {
        try {
            $start_date = ($request->has('start_date')) 
                ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))
                : Carbon::now()->subYears(5);

            $end_date = ($request->has('end_date')) 
                ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))
                : Carbon::now();

            $income = $this->service->income($start_date, $end_date);
            $income_by_period = $this->service->incomeByPeriod($end_date->clone()->subYears(2), $end_date);
            $future_credit_value = $this->service->futureInvoiceAmounts();
            $total_loans = $this->service->totalLoans($start_date, $end_date);
            $wallets_values = $this->service->incomeByWallet($start_date, $end_date);
            $output_by_category = $this->service->expensesByCategory($end_date->clone()->subYears(1), $end_date);
            $loans = $this->service->loans($start_date, $end_date);

            return view('reports.index', compact('income', 'income_by_period', 'future_credit_value', 'total_loans', 'wallets_values', 'loans', 'output_by_category'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function loans(Request $request)
    {
        try {
            $start_date = ($request->has('start_date')) 
                ? Carbon::createFromFormat('Y-m-d', $request->get('start_date'))
                : Carbon::now()->subYears(5);

            $end_date = ($request->has('end_date')) 
                ? Carbon::createFromFormat('Y-m-d', $request->get('end_date'))
                : Carbon::now();

            $owners = $this->ownerService->listOther();

            if ($owners->count() < 1) 
                throw new BaseException(__('There Are No Owners'));
    
            $owner_id = $request->get('owner_id', $owners->first()?->id);
            $ownerLoans = $this->service->ownerLoansTransactions($owner_id, $start_date, $end_date);

            return view('reports.loans', compact('owners', 'owner_id', 'start_date', 'end_date', 'ownerLoans'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }
}
