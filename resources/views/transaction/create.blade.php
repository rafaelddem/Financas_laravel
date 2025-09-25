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
        <h2 class="card-title">{{__('Fill out the form')}}</h2>
        <div class="flex-container">
            <div class="col_50 md_col">
                <label for="title">{{__('Title')}}:</label>
                <input type="text" form="form-insert" name="title" id="title" value="{{old('title')}}" placeholder="{{__('Title')}}" required>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <label for="transaction_date">{{__('Transaction Date')}}:</label>
                <input type="date" form="form-insert" name="transaction_date" id="transaction_date" value="{{old('transaction_date', Carbon::now()->format('Y-m-d'))}}" placeholder="{{__('Transaction Date')}}" required>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <label for="processing_date">{{__('Processing Date')}}:</label>
                <input type="date" form="form-insert" name="processing_date" id="processing_date" value="{{old('processing_date', Carbon::now()->format('Y-m-d'))}}" placeholder="{{__('Processing Date')}}" required>
            </div>
            <div class="col_33 sm_col">
                <label for="category_id">{{__('Category')}}:</label>
                <select name="category_id" form="form-insert" id="category_id">
                    @foreach ($categories as $presentation)
                        <option value='{{ $presentation->id }}' data-relevance="{{ $presentation->relevance }}" {{ old('category_id') == $presentation->id ? 'selected' : '' }}>{{ $presentation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_33 sm_col">
                <label for="relevance">{{__('Relevance')}}:</label>
                <select name="relevance" form="form-insert" id="relevance">
                    @foreach (Relevance::cases() as $relevance)
                        <option value='{{ $relevance->value }}' {{ old('relevance') == $relevance->value ? 'selected' : '' }}>{{ __($relevance->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_33 sm_col">
                <label for="payment_method_id">{{__('Payment Method')}}:</label>
                <select name="payment_method_id" form="form-insert" id="payment_method_id">
                    @foreach ($paymentMethods as $paymentMethod)
                        <option value='{{ $paymentMethod->id }}' data-type="{{ $paymentMethod->type }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="flex-container">
            <div class="col_25 md_col_50 sm_col">
                <label for="source_wallet_id">{{__('Source Wallet')}}:</label>
                <select name="source_wallet_id" form="form-insert" id="source_wallet_id">
                    @foreach ($sourceWallets as $sourceWallet)
                        <option value='{{ $sourceWallet->id }}' data-owner="{{ $sourceWallet->owner_id }}" data-wallet="{{ $sourceWallet->id }}" {{ old('source_wallet_id') == $sourceWallet->id ? 'selected' : '' }}>
                            {{ $sourceWallet->owner->name }} > {{ $sourceWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <label for="destination_wallet_id">{{__('Destination Wallet')}}:</label>
                <select name="destination_wallet_id" form="form-insert" id="destination_wallet_id">
                    @foreach ($destinationWallets as $destinationWallet)
                        <option value='{{ $destinationWallet->id }}' data-owner="{{ $destinationWallet->owner_id }}" data-wallet="{{ $destinationWallet->id }}" {{ old('destination_wallet_id') == $destinationWallet->id ? 'selected' : '' }}>
                            {{ $destinationWallet->owner->name }} > {{ $destinationWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="div_card" class="col_25 md_col_50 sm_col">
                <label for="card_id">{{__('Card')}}:</label>
                <select name="card_id" form="form-insert" id="card_id" required>
                </select>
            </div>
            <div id="div_installments" class="col_25 md_col_50 sm_col">
                <label for="installment_number">{{__('Installments')}}:</label>
                <input type="number" form="form-insert" name="installment_number" id="installment_number" value="{{old('installment_number', 1)}}" min="1" max="36" required>
            </div>
        </div>
        <div class="flex-container" id="base_values">
            <div class="col_20 md_col_50 sm_col">
                <label for="gross_value">{{__('Gross Value')}}:</label>
                <input type="text" form="form-insert" name="gross_value" data-name="gross_value" class="money" value="{{old('gross_value', '0,00')}}" placeholder="{{__('Gross Value')}}" required pattern="^(?!0,00$).+" title="{{__('A value needs to be entered')}}">
            </div>
            <div class="col_20 md_col_50 sm_col">
                <label for="discount_value">{{__('Discount Value')}}:</label>
                <input type="text" form="form-insert" name="discount_value" data-name="discount_value" class="money" value="{{old('discount_value', '0,00')}}" placeholder="{{__('Discount Value')}}" required>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <label for="interest_value">{{__('Interest Value')}}:</label>
                <input type="text" form="form-insert" name="interest_value" data-name="interest_value" class="money" value="{{old('interest_value', '0,00')}}" placeholder="{{__('Interest Value')}}" required>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <label for="rounding_value">{{__('Rounding Value')}}:</label>
                <input type="text" form="form-insert" name="rounding_value" data-name="rounding_value" class="money" value="{{old('rounding_value', '0,00')}}" placeholder="{{__('Rounding Value')}}" required>
            </div>
            <div class="col_20 md_col_50 sm_col">
                <label>{{__('Net Value')}}:</label>
                <input type="text" form="form-insert" name="net_value" data-name="net_value" class="money" value="0,00" placeholder="{{__('Net Value')}}" disabled>
            </div>
        </div>

        <div id="installmentFields"></div>

        <div id="transction_credit_template" style="display: none;">
            <div class="flex-container">
                <div class="col_10 md_col_20 sm_col">
                    <h4><label id="installmentData" class="label-as-input">Parcela #1</label></h4>
                </div>
                <div class="col_15 md_col_40 sm_col">
                    <label>{{__('Installment Date')}}:</label>
                    <input type="date" form="form-insert" data-name="installment_date" placeholder="{{__('Installment Date')}}">
                </div>
                <div class="col_15 md_col_40 sm_col">
                    <label>{{__('Gross Value')}}:</label>
                    <input type="text" form="form-insert" data-name="gross_value" class="money" value="0,00" placeholder="{{__('Gross Value')}}" required>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <label>{{__('Discount Value')}}:</label>
                    <input type="text" form="form-insert" data-name="discount_value" class="money" value="0,00" placeholder="{{__('Discount Value')}}" required>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <label>{{__('Interest Value')}}:</label>
                    <input type="text" form="form-insert" data-name="interest_value" class="money" value="0,00" placeholder="{{__('Interest Value')}}" required>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <label>{{__('Rounding Value')}}:</label>
                    <input type="text" form="form-insert" data-name="rounding_value" class="money" value="0,00" placeholder="{{__('Rounding Value')}}" required>
                </div>
                <div class="col_15 md_col_50 sm_col">
                    <label>{{__('Net Value')}}:</label>
                    <input type="text" form="form-insert" data-name="net_value" class="money" value="0,00" placeholder="{{__('Net Value')}}" disabled>
                </div>
            </div>
        </div>

        <div class="flex-container">
            <div class="col">
                <label for="description">{{__('Description')}}:</label>
                <textarea form="form-insert" class="textarea" name="description" rows="3" placeholder="{{__('Default Placeholder')}}"></textarea>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action="{{route('transaction.store')}}"> @csrf </form>
    </div>
@endsection
