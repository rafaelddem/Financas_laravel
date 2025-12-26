@php
use App\Enums\Relevance;
use Carbon\Carbon;
@endphp

@push('head-script')
    <script src="{{ asset('js/transaction/script.js') }}" defer></script>
@endpush('head-script')

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
                <label>{{$transaction->title}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Transaction Date')}}:</label></h4>
                <label>{{$transaction->transaction_date->format('d/m/Y')}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Processing Date')}}:</label></h4>
                <label>{{$transaction->processing_date->format('d/m/Y')}}</label>
            </div>
        </div>

        <div class="flex-container">
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Source Wallet')}}:</label></h4>
                <label>{{__($transaction->sourceWallet->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Destination Wallet')}}:</label></h4>
                <label>{{__($transaction->destinationWallet->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Category')}}:</label></h4>
                <label>{{$transaction->category->name}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Relevance')}}:</label></h4>
                <label>{{__($transaction->relevance->name)}}</label>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Payment Method')}}:</label></h4>
                <label>{{__($transaction->paymentMethod->name)}}</label>
            </div>
            @if($transaction->card_id != null)
            <div class="col_25 md_col_50 sm_col">
                <h4><label>{{__('Card')}}:</label></h4>
                <label>{{__($transaction->card->name)}}</label>
            </div>
            @endif
        </div>

        @if($transaction->description != null)
            <div class="flex-container">
                <div class="col">
                    <h4><label>{{__('Description')}}:</label></h4>
                    <label>{{$transaction->description}}</label>
                </div>
            </div>
        @endif

        <div class="flex-container">
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Gross Value')}}:</label></h4>
                <label>{{__(\App\Helpers\MoneyHelper::format($transaction->gross_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Discount Value')}}:</label></h4>
                <label>{{__(\App\Helpers\MoneyHelper::format($transaction->discount_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Interest Value')}}:</label></h4>
                <label>{{__(\App\Helpers\MoneyHelper::format($transaction->interest_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Rounding Value')}}:</label></h4>
                <label>{{__(\App\Helpers\MoneyHelper::format($transaction->rounding_value))}}</label>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <h4><label>{{__('Net Value')}}:</label></h4>
                <label>{{__($transaction->net_value_formatted)}}</label>
            </div>
        </div>

        @if($transaction->installments->count() > 0)
            <div class="flex-container">
                <div class="col_10 md_col_20 sm_col">
                    <h4><label>{{__('Installment')}}:</label></h4>
                </div>
                <div class="col_15 md_col_40 sm_col">
                    <h4><label>{{__('Installment Date')}}:</label></h4>
                </div>
                <div class="col_15 md_col_40 sm_col">
                    <h4><label>{{__('Gross Value')}}:</label></h4>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <h4><label>{{__('Discount Value')}}:</label></h4>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <h4><label>{{__('Interest Value')}}:</label></h4>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <h4><label>{{__('Rounding Value')}}:</label></h4>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <h4><label>{{__('Net Value')}}:</label></h4>
            </div>
            </div>
            @foreach ($transaction->installments as $installment)
                <div class="flex-container">
                    <div class="col_10 md_col_20 sm_col">
                        <label>{{$installment->installment_number}}ª</label>
                    </div>
                    <div class="col_15 md_col_40 sm_col">
                        <label>{{__($installment->installment_date->format('d/m/Y'))}}</label>
                    </div>
                    <div class="col_15 md_col_40 sm_col">
                        <label>{{__(\App\Helpers\MoneyHelper::format($installment->gross_value))}}</label>
                    </div>
                    <div class="col_15 md_col_50 sm_col">
                        <label>{{__(\App\Helpers\MoneyHelper::format($installment->discount_value))}}</label>
                    </div>
                    <div class="col_15 md_col_50 sm_col">
                        <label>{{__(\App\Helpers\MoneyHelper::format($installment->interest_value))}}</label>
                    </div>
                    <div class="col_15 md_col_50 sm_col">
                        <label>{{__(\App\Helpers\MoneyHelper::format($installment->rounding_value))}}</label>
                    </div>
                    <div class="col_15 md_col_50 sm_col">
                        <label>{{__(\App\Helpers\MoneyHelper::format($installment->net_value))}}</label>
                    </div>
                </div>
            @endforeach
        @endif

        <div class="flex-container"><div class="col"></div></div>

        <div class="flex-container"><div class="col"></div></div>

        <div class="flex-container">
            <div class="col">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->previous()}}'">
            </div>
        </div>
    </div>
@endsection
