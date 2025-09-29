<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
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
        $this->service = app(InvoiceService::class);
        $this->walletService = app(WalletService::class);
        $this->cardService = app(CardService::class);
    }

    public function index(Request $request)
    {
        $message = $request->get('message');
        $openInvoices = new Collection();
        $closedInvoices = new Collection();
        $paidInvoices = new Collection();
        $startDate = Carbon::now()->subYear();
        $endDate = Carbon::now();
        $walletId = $request->get('wallet_id');
        $wallets = new Collection();
        $cardId = $request->get('card_id');
        $cards = new Collection();

        if ($request->has('start_date', 'end_date')) {
            $startDate = Carbon::createFromFormat('Y-m-d', $request->get('start_date'));
            $endDate = Carbon::createFromFormat('Y-m-d', $request->get('end_date'));
        }

        try {
            $wallets = $this->walletService->listWalletsWithCreditCards();
            if ($walletId) {
                $cards = $this->cardService->listCredit($walletId);
            }

            $openInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Open, $walletId, $cardId);
            $paidInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Paid, $walletId, $cardId);
            $closedInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Closed, $walletId, $cardId);
            $overdueInvoices = $this->service->listInvoices($startDate, $endDate, InvoiceStatus::Overdue, $walletId, $cardId);
            $closedInvoices = $closedInvoices->merge($overdueInvoices)->sortByDesc('status');

            return view('invoice.index', compact('openInvoices', 'closedInvoices', 'paidInvoices', 'message', 'startDate', 'endDate', 'wallets', 'walletId', 'cards', 'cardId'));
        } catch (BaseException $exception) {
            $message = __($exception->getMessage());
        } catch (\Throwable $th) {
            $message = __(self::DEFAULT_CONTROLLER_ERROR);
        }

        return view('invoice.index', compact('openInvoices', 'closedInvoices', 'paidInvoices', 'startDate', 'endDate', 'wallets', 'walletId', 'cards', 'cardId'))->withErrors(compact('message'));
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
