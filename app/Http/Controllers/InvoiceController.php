<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Enums\Period;
use App\Exceptions\BaseException;
use App\Services\CardService;
use App\Services\InvoiceService;
use App\Services\WalletService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    private InvoiceService $service;
    private WalletService $walletService;
    private CardService $cardService;

    public function __construct()
    {
        parent::__construct();

        $this->service = app(InvoiceService::class);
        $this->walletService = app(WalletService::class);
        $this->cardService = app(CardService::class);
    }

    public function index(Request $request)
    {
        try {
            [$startDate, $endDate] = ($request->has('start_date', 'end_date'))
                ? [Carbon::createFromFormat('Y-m-d', $request->get('start_date'))->startOfDay(), Carbon::createFromFormat('Y-m-d', $request->get('end_date'))->endOfDay()]
                : Period::LAST_YEAR->getDateLimits();

            $message = $request->get('message');
            $walletId = $request->get('wallet_id');
            $cardId = $request->get('card_id');
            $wallets = $this->walletService->listWalletsWithCreditCards();
            $cards = $walletId
                ? $this->cardService->listCredit($walletId)
                : new Collection();

            $openInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Open, $walletId, $cardId);
            $paidInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Paid, $walletId, $cardId);
            $closedInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Closed, $walletId, $cardId);
            $overdueInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Overdue, $walletId, $cardId);
            $closedInvoices = $closedInvoices->merge($overdueInvoices)->sortByDesc('status');

            return view('invoice.index', ['top_bar_data' => $this->top_bar_data] + compact('openInvoices', 'closedInvoices', 'paidInvoices', 'message', 'startDate', 'endDate', 'wallets', 'walletId', 'cards', 'cardId'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('home'))->withErrors(compact('message'));
    }

    public function details(Request $request)
    {
        try {
            [$invoice, $installments, $futureInstallments] = $this->service->details($request->invoice_id);

            return view('invoice.details', ['top_bar_data' => $this->top_bar_data] + compact('invoice', 'installments', 'futureInstallments'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('invoice.list'))->withErrors(compact('message'));
    }

    public function pay(Request $request)
    {
        $message = '';

        try {
            $this->service->pay($request->id);

            $start_date = $request->get('filter_start_date');
            $end_date = $request->get('filter_end_date');
            $wallet_id = $request->get('filter_wallet');
            $card_id = $request->get('filter_card');

            $message = __('Action executed successfully.');
            return redirect(route('invoice.list', compact('message', 'start_date', 'end_date', 'wallet_id', 'card_id')));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return redirect(route('invoice.list'))->withErrors(compact('message'));
    }
}
