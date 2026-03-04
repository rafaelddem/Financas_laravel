<?php

namespace App\Http\Controllers;

use App\Enums\Period;
use App\Exceptions\BaseException;
use App\Services\DateService;
use App\Services\OwnerService;
use App\Services\ReportService;
use App\Services\WalletService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    private ReportService $service;
    private OwnerService $ownerService;
    private WalletService $walletService;

    public function __construct()
    {
        $this->service = app(ReportService::class);
        $this->ownerService = app(OwnerService::class);
        $this->walletService = app(WalletService::class);
    }

    public function index(Request $request, DateService $dateService)
    {
        try {
            [$start_date, $end_date] = $dateService->extractFilterDateFromRequest(Period::LAST_YEAR, $request->get('start_date'), $request->get('end_date'));

            $income = $this->service->income($start_date, $end_date);
            $income_by_period = $this->service->incomeByPeriod($end_date->clone()->subYears(2), $end_date);
            $future_credit_value = $this->service->futureInvoiceAmounts();
            $total_loans = $this->service->totalLoans($start_date, null);
            $wallets_values = $this->service->incomeByWallet($start_date, $end_date);
            $output_by_category = $this->service->expensesByCategory($end_date->clone()->subYears(1), $end_date);
            $loans = $this->service->loans($start_date, null);

            return view('reports.index', compact('income', 'income_by_period', 'future_credit_value', 'total_loans', 'wallets_values', 'loans', 'output_by_category'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function loans(Request $request, DateService $dateService)
    {
        try {
            [$start_date, $end_date] = $dateService->extractFilterDateFromRequest(Period::LAST_YEAR, $request->get('start_date'), $request->get('end_date'));

            $owners = $this->ownerService->listOther();

            if ($owners->count() < 1) 
                throw new BaseException(__('There Are No Owners'));
    
            $owner_id = $request->get('owner_id', $owners->first()?->id);
            $ownerLoans = $this->service->ownerLoansTransactions($owner_id, $start_date, $end_date);

            if ($request->print) {
                $owner_name = $owners->find($owner_id)->name;
                $report = Pdf::loadView('reports.loans_print', compact('owner_name', 'owner_id', 'start_date', 'end_date', 'ownerLoans'))
                    ->setPaper('a4', 'landscape')
                    ->setOptions(['isRemoteEnabled' => true, 'defaultFont' => 'DejaVu Sans']);

                $reportTitle = __('Report File Name from :origin', ['origin' => "Emprestimos"]);
                $reportTitle .= '_' . $owner_name;
                $reportTitle .= '_' . Carbon::now()->format('YmdHis') . '.pdf';
                $reportTitle = iconv('UTF-8', 'ASCII//TRANSLIT', strtolower($reportTitle));

                return $report->download($reportTitle);
            }

            return view('reports.loans', compact('owners', 'owner_id', 'start_date', 'end_date', 'ownerLoans'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function transactionByWallet(Request $request, DateService $dateService)
    {
        try {
            [$start_date, $end_date] = $dateService->extractFilterDateFromRequest(Period::LAST_30_DAYS, $request->get('start_date'), $request->get('end_date'));

            $wallets = $this->walletService->listWalletsFromOwner(env('MY_OWNER_ID'));

            if ($wallets->count() < 1) 
                throw new BaseException(__('There Are No Wallets'));

            $wallet_id = $request->get('wallet_id');
            $filterWallet = $wallet_id != 0
                ? [$request->get('wallet_id')]
                : null;

            $transactions = $this->service->listTransactionsByWallets($start_date, $end_date, $filterWallet);

            return view('reports.transactions', compact('wallets', 'wallet_id', 'start_date', 'end_date', 'transactions'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }
}
