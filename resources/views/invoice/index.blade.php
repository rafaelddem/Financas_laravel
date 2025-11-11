@php
use App\Enums\InvoiceStatus;
use Carbon\Carbon;
@endphp

@push('head-script')
    <script src="{{ asset('js/invoice/script.js') }}" defer></script>
@endpush('head-script')

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Invoices')}}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Filters')}}</h2>
        <div class="flex-container">
            <div class="col_20">
                <label for="title">{{__('Start Date')}}:</label>
                <input type="date" form="form-filter" name="start_date" id="start_date" value="{{$startDate->format('Y-m-d')}}" required>
            </div>
            <div class="col_20">
                <label for="title">{{__('End Date')}}:</label>
                <input type="date" form="form-filter" name="end_date" id="end_date" value="{{$endDate->format('Y-m-d')}}" required>
            </div>
            <div class="col_20">
                <label for="wallet_id">{{__('Source Wallet')}}:</label>
                <select name="wallet_id" form="form-filter" id="wallet_id">
                    <option value='0' data-owner="" data-wallet="">{{__('All Wallets')}}</option>
                    @foreach ($wallets as $wallet)
                        <option value='{{ $wallet->id }}' data-owner="{{ $wallet->owner_id }}" data-wallet="{{ $wallet->id }}" @if($walletId == $wallet->id) selected @endIf>
                            {{ $wallet->owner->name }} > {{ $wallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="div_card" class="col_20">
                <label for="card_id">{{__('Card')}}:</label>
                <select form="form-filter" name="card_id" id="card_id" required>
                    <option value='0' data-owner="" data-wallet="">{{__('All Cards')}}</option>
                    @foreach ($cards as $card)
                        <option value='{{ $card->id }}' @if($cardId == $card->id) selected @endIf> {{ $card->name }} </option>
                    @endforeach
                </select>
            </div>
            <div class="col_20">
                <input class="button-as-input" type="submit" form="form-filter" value="{{__('Filter')}}">
            </div>
            <form method="get" id="form-filter" action="{{route('invoice.list')}}"></form>
        </div>
    </div>
    @if(!$closedInvoices->isEmpty())
        <div class="presentation">
            <h2 class="card-title">{{__('Unpaid Invoice')}}</h2>
            <div class="col">
                <table>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    @foreach($closedInvoices as $invoice)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $invoice->card->name }} | {{ $invoice->start_date->format('d/m/Y') }} - {{ $invoice->end_date->format('d/m/Y') }} | {{ $invoice->value_formatted }}
                                @if($invoice->due_date < Carbon::now())
                                    <span class="tag">{{__('Overdue')}}</span>
                                @endif
                            </span>
                            <button type="button"
                                class="open-confirm"
                                data-id="{{ $invoice->id }}"
                                data-subtitle="{{ $invoice->card->name }} | {{ $invoice->start_date->format('d/m/Y') }} - {{ $invoice->end_date->format('d/m/Y') }}">
                                {{ __('Pay') }}
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    @endif
    <div class="presentation">
        <h2 class="card-title">{{__('Open Invoices')}}</h2>
        <div class="col">
            <table>
                @foreach($openInvoices as $invoice)
                <tr>
                    <td class="td-item">
                        <span class="td-content">
                            {{ $invoice->card->name }} | {{ $invoice->start_date->format('d/m/Y') }} - {{ $invoice->end_date->format('d/m/Y') }} | {{ $invoice->value_formatted }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Paid Invoices')}}</h2>
        <div class="col">
            <table>
                @foreach($paidInvoices as $invoice)
                <tr>
                    <td class="td-item">
                        <span class="td-content">
                            {{ $invoice->card->name }} | {{ $invoice->start_date->format('d/m/Y') }} - {{ $invoice->end_date->format('d/m/Y') }} | {{ $invoice->value_formatted }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>

    <div class="popupConfirmation" style="display: none;">
        <div class="presentation">
            <h3>{{ __('Do you confirm the invoice payment? This action cannot be undone.') }}</h3>
            <h4 id="popup-subtitle"></h4>
            <br>
            <form method="POST" id="confirmation-form" action="{{ route('invoice.pay') }}">
                @csrf
                <input type="hidden" name="id" id="popup-id">
                <input type="hidden" name="filter_start_date" id="popup-filter-start-date">
                <input type="hidden" name="filter_end_date" id="popup-filter-end-date">
                <input type="hidden" name="filter_wallet" id="popup-filter-wallet">
                <input type="hidden" name="filter_card" id="popup-filter-card">
                <div class="td-buttons">
                    <button type="submit" class="confirm">{{ __('Yes') }}</button>
                    <button type="button" class="cancel" onclick="closePopup()">{{ __('No') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
