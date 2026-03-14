@php
use App\Enums\PaymentType;
@endphp

@extends('layout')

@push('head-script')
    <script src="{{ asset('js/charts.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{ __('Transactions') }}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Filters')}}</h2>
        <div class="flex-container">
            <div class="col_40 md_col_50 sm_col">
                <label for="wallet_id">{{ __('Wallet') }}:</label>
                @if($wallets->count() > 0)
                    <select name="wallet_id" form="form-loans">
                        <option value="0">{{__('All_f')}}</option>
                        @foreach($wallets as $wallet)
                            <option value="{{$wallet->id}}" @if($wallet->id == $wallet_id) selected @endif>
                                {{$wallet->name}} 
                                @if(!$wallet->active) ({{__('Inactive')}}) @endif
                            </option>
                        @endforeach
                    </select>
                @else
                    <select name="wallet_id" disabled>
                        <option value="" selected hidden>{{ __('There Are No Wallets') }}</option>
                    </select>
                @endif
            </div>
            <div class="col_15 md_col_25 sm_col">
                <label for="title">{{__('Start Date')}}:</label>
                <input type="date" form="form-loans" name="start_date" id="start_date" value="{{$start_date->format('Y-m-d')}}" required>
            </div>
            <div class="col_15 md_col_25 sm_col">
                <label for="title">{{__('End Date')}}:</label>
                <input type="date" form="form-loans" name="end_date" id="end_date" value="{{$end_date->format('Y-m-d')}}" required>
            </div>
            <div class="col_15 md_col_50 sm_col">
                <input class="button-as-input" type="submit" form="form-loans" value="{{ __('Filter') }}" @if($wallets->count() == 0) disabled @endif>
            </div>
            <div class="col_15 md_col_50 sm_col">
                <input class="button-as-input" type="button" value="{{__('New_f')}}" onclick="window.location='{{app('url')->route('transaction.create')}}'">
            </div>
        </div>
        <form method="get" id="form-loans" action="{{ route('reports.transactionByWallet') }}" enctype="multipart/form-data"> @csrf </form>
    </div>
    
    <div class="presentation">
        <h2 class="card-title">{{__('Transactions')}}</h2>
        @foreach($transactions as $transaction)
            <div class="flex-container">
                <div class="col_15">
                    {{$transaction->transaction_date->format('d/m/Y')}}
                </div>
                <div class="col_30">
                    @if($transaction->sourceWallet->owner_id == env('MY_OWNER_ID'))
                        {{$transaction->sourceWallet->name}} 
                    @else
                        {{$transaction->sourceWallet->owner->name}} 
                    @endif
                    >
                    @if($transaction->destinationWallet->owner_id == env('MY_OWNER_ID'))
                        {{$transaction->destinationWallet->name}} 
                    @else
                        {{$transaction->destinationWallet->owner->name}} 
                    @endif
                    | <span class="tag">{{__(PaymentType::translate($transaction->paymentMethod->type->value))}}</span>
                </div>
                <div class="col_40">
                    <button type="button" class="button_small" onclick="window.location='{{ route('transaction.show', ['id' => $transaction->id]) }}'" title="{{__('Transaction Details')}}">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                    {{$transaction->title}}
                </div>
                <div class="col_15">
                    {{ $transaction->net_value_formatted }}
                    @if($transaction->destinationWallet->owner_id == env('MY_OWNER_ID'))
                    @endif
                </div>
            </div>
        @endforeach
        <br>
    </div>
@endsection
