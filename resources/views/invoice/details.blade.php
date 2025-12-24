@php
use App\Enums\InvoiceStatus;
use Carbon\Carbon;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Invoice Details From Card', ['cardName' => $invoice->card->name, 'invoiceStartDate' => $invoice->start_date->format('d/m/Y'), 'invoiceEndDate' => $invoice->end_date->format('d/m/Y')])}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{ route('invoice.list') }}'">
            </div>
            <div class="col">
                <table>
                    @foreach($installments as $installment)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $installment->installment_date->format('d/m/Y') }} | {{ $installment->net_value_formatted }} | {{ $installment->transaction->title }}
                                @if ($installment->installment_total > 1)
                                    ({{ $installment->installment_number }} de {{ $installment->installment_total }})
                                @endif
                                @php( $destinationOwner = $installment->transaction->destinationWallet->owner )
                                @if ($destinationOwner->id != env('SYSTEM_ID'))
                                    <span class="tag">{{__($destinationOwner->name)}}</span>
                                @endif
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
            <div class="col">
                <h3>{{__('Total Amount')}}: {{$invoice->value_formatted}}</h3>
            </div>
        </div>
    </div>
    @if (count($futureInstallments) > 0)
    <div class="presentation">
        <div class="col">
            <h2 class="card-title">{{__('Future Installments')}}</h2>
            <table>
                @foreach($futureInstallments as $installment)
                <tr>
                    <td class="td-item">
                        <span class="td-content">
                            {{ $installment->installment_date->format('d/m/Y') }} | {{ $installment->net_value_formatted }} | {{ $installment->transaction->title }}
                            @if ($installment->installment_total > 1)
                                ({{ $installment->installment_number }} de {{ $installment->installment_total }})
                            @endif
                            @php( $destinationOwner = $installment->transaction->destinationWallet->owner )
                            @if ($destinationOwner->id != env('SYSTEM_ID'))
                                <span class="tag">{{__($destinationOwner->name)}}</span>
                            @endif
                        </span>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
    @endif
@endsection
