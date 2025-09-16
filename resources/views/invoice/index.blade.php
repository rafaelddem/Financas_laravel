@php
use App\Enums\InvoiceStatus;
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
        <h2 class="card-title">{{__('Open Invoices')}}</h2>
        <div class="row">
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
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Unpaid Invoice')}}</h2>
        <div class="row">
            <div class="col">
                <table>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    @foreach($closedInvoices as $invoice)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $invoice->card->name }} | {{ $invoice->start_date->format('d/m/Y') }} - {{ $invoice->end_date->format('d/m/Y') }} | {{ $invoice->value_formatted }}
                                @if($invoice->status == InvoiceStatus::Overdue->value)
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
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Paid Invoices')}}</h2>
        <div class="row">
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
    </div>

    <div class="popupConfirmation" style="display: none;">
        <div class="presentation">
            <h3>{{ __('Do you confirm the invoice payment? This action cannot be undone.') }}</h3>
            <h4 id="popup-subtitle"></h4>
            <br>
            <form method="POST" id="confirmation-form" action="{{ route('invoice.pay') }}">
                @csrf
                <input type="hidden" name="id" id="popup-id">
                <div class="td-buttons">
                    <button type="submit" class="confirm">{{ __('Yes') }}</button>
                    <button type="button" class="cancel" onclick="closePopup()">{{ __('No') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
