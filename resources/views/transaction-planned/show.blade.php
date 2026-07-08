@php
use App\Enums\Relevance;
use App\Helpers\MoneyHelper;
use Carbon\Carbon;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transactions')}}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Transaction Details')}}</h2>
        <div class="flex-container">
            <div class="col_50 md_col">
                <h4><label>{{__('Title')}}:</label></h4>
                <label>{{$transactionPlanned->title}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Transaction Date')}}:</label></h4>
                <label>{{$transactionPlanned->transaction_date->format('d/m/Y')}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Transaction Date')}}:</label></h4>
                <label>{{$transactionPlanned->transaction_date->format('d/m/Y')}}</label>
            </div>
        </div>

        <div class="flex-container">
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Source Wallet')}}:</label></h4>
                <label>{{__($transactionPlanned->sourceWallet->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Destination Wallet')}}:</label></h4>
                <label>{{__($transactionPlanned->destinationWallet->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Category')}}:</label></h4>
                <label>{{$transactionPlanned->category->name}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Relevance')}}:</label></h4>
                <label>{{__($transactionPlanned->relevance->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Payment Method')}}:</label></h4>
                <label>{{__($transactionPlanned->paymentMethod->name)}}</label>
            </div>
            @if($transactionPlanned->card_id != null)
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Card')}}:</label></h4>
                <label>{{__($transactionPlanned->card->name)}}</label>
            </div>
            @endif
        </div>

        @if($transactionPlanned->description != null)
            <div class="flex-container">
                <div class="col">
                    <h4><label>{{__('Description')}}:</label></h4>
                    <label>{{$transactionPlanned->description}}</label>
                </div>
            </div>
        @endif

        <div class="flex-container">
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Gross Value')}}:</label></h4>
                <label>{{__(MoneyHelper::format($transactionPlanned->gross_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Discount Value')}}:</label></h4>
                <label>{{__(MoneyHelper::format($transactionPlanned->discount_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Interest Value')}}:</label></h4>
                <label>{{__(MoneyHelper::format($transactionPlanned->interest_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Rounding Value')}}:</label></h4>
                <label>{{__(MoneyHelper::format($transactionPlanned->rounding_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Net Value')}}:</label></h4>
                <label>{{__($transactionPlanned->net_value_formatted)}}</label>
            </div>
        </div>

        @if($transactionPlanned->installments->count() > 0)
            <div class="flex-container"><div class="col"></div></div>
            <div class="flex-container"><div class="col"></div></div>
            <div class="flex-container">
                <div class="col">
                    <h4><label>{{__('Installments')}}</label></h4>
                </div>
            </div>
            @foreach ($transactionPlanned->installments as $installment)
                <div class="separator-with-text"><span>{{__('Installment :number from :total', ['number' => $installment->installment_number, 'total' => $installment->installment_total])}}</span></div>
                <div class="flex-container">
                    <div class="col_15 md_col_33 sm_col">
                        <h4><label>{{__('Installment Date')}}:</label></h4>
                        <label>{{__($installment->installment_date->format('d/m/Y'))}}</label>
                    </div>
                    <div class="col_20 md_col_33 sm_col">
                        <h4><label>{{__('Gross Value')}}:</label></h4>
                        <label>{{__(MoneyHelper::format($installment->gross_value))}}</label>
                    </div>
                    <div class="col_15 md_col_33 sm_col">
                        <h4><label>{{__('Discount Value')}}:</label></h4>
                        <label>{{__(MoneyHelper::format($installment->discount_value))}}</label>
                    </div>
                    <div class="col_15 md_col_33 sm_col">
                        <h4><label>{{__('Interest Value')}}:</label></h4>
                        <label>{{__(MoneyHelper::format($installment->interest_value))}}</label>
                    </div>
                    <div class="col_15 md_col_33 sm_col">
                        <h4><label>{{__('Rounding Value')}}:</label></h4>
                        <label>{{__(MoneyHelper::format($installment->rounding_value))}}</label>
                    </div>
                    <div class="col_20 md_col_33 sm_col">
                        <h4><label>{{__('Net Value')}}:</label></h4>
                        <label>{{__(MoneyHelper::format($installment->net_value))}}</label>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="flex-container">
            @if($totalTransactionPlanned > 1)
            <div class="col_25 md_col_50 sm_col">
                <label class="label-as-input"><b>{{__('More :qtd Transaction Planned', ['qtd' => $totalTransactionPlanned - 1])}}</b></label>
            </div>
            @endif
        </div>

        <div class="flex-container"><div class="col"></div></div>

        <div class="flex-container"><div class="col"></div></div>

        <div class="flex-container">
            <div class="col_20 md_col_50 sm_col">
                <div class="td-buttons">
                    <button type="submit" class="col" form="form-approve">{{__('Approve')}}</button>
                </div>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <div class="td-buttons">
                    <button type="submit" class="col" form="form-edit">{{__('Edit')}}</button>
                </div>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <div class="td-buttons">
                    <input type="hidden" form="form-delete" name="id" value={{$transactionPlanned->id}}>
                    <button type="submit" class="col" form="form-delete">{{__('Delete this :name', ['name' => __('Transaction')])}}</button>
                </div>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <div class="td-buttons">
                    <input type="hidden" form="form-delete-all" name="transaction_planned_id" value={{$transactionPlanned->transaction_planned_id}}>
                    <button type="submit" class="col" form="form-delete-all">{{__('Delete All (:qtd)', ['qtd' => $totalTransactionPlanned])}}</button>
                </div>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <div class="td-buttons">
                    <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-planned.list')}}'">
                </div>
            </div>
        </div>
        <form method="get" id="form-approve" action="{{route('transaction-planned.approve', ['id' => $transactionPlanned->id])}}"></form>
        <form method="get" id="form-edit" action="{{route('transaction.edit', ['id' => $transactionPlanned->id])}}"></form>
        <form method="post" id="form-delete" action="{{route('transaction-planned.destroy')}}"> @csrf @method('DELETE') </form>
        <form method="post" id="form-delete-all" action="{{route('transaction-planned.destroy')}}"> @csrf @method('DELETE') </form>
    </div>
@endsection
