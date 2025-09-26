@php
use App\Enums\Relevance;
use Carbon\Carbon;
@endphp

@push('head-script')
    <script src="{{ asset('js/transaction-base/script.js') }}" defer></script>
@endpush('head-script')

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Bases')}}</h1>
    </div>
    <div class="presentation">
        <h2 class="card-title">{{__('Fill out the form')}}</h2>
        <div class="flex-container">
            <div class="col_50 md_col">
                <label for="title">{{__('Title')}}:</label>
                <input type="text" form="form-insert" name="title" id="title" value="{{old('title')}}" placeholder="{{__('Title')}}" required>
            </div>
            <div class="col_50 sm_col">
                <label for="category_id">{{__('Category')}}:</label>
                <select name="category_id" form="form-insert" id="category_id">
                    @foreach ($categories as $presentation)
                        <option value='{{ $presentation->id }}' data-relevance="{{ $presentation->relevance }}" {{ old('category_id') == $presentation->id ? 'selected' : '' }}>{{ $presentation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_50 md_col">
                <label for="source_wallet_id">{{__('Source Wallet')}}:</label>
                <select name="source_wallet_id" form="form-insert" id="source_wallet_id">
                    @foreach ($sourceWallets as $sourceWallet)
                        <option value='{{ $sourceWallet->id }}' data-owner="{{ $sourceWallet->owner_id }}" data-wallet="{{ $sourceWallet->id }}" {{ old('source_wallet_id') == $sourceWallet->id ? 'selected' : '' }}>
                            {{ $sourceWallet->owner->name }} > {{ $sourceWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col_50 md_col">
                <label for="destination_wallet_id">{{__('Destination Wallet')}}:</label>
                <select name="destination_wallet_id" form="form-insert" id="destination_wallet_id">
                    @foreach ($destinationWallets as $destinationWallet)
                        <option value='{{ $destinationWallet->id }}' data-owner="{{ $destinationWallet->owner_id }}" data-wallet="{{ $destinationWallet->id }}" {{ old('destination_wallet_id') == $destinationWallet->id ? 'selected' : '' }}>
                            {{ $destinationWallet->owner->name }} > {{ $destinationWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col_50 sm_col">
                <label for="payment_method_id">{{__('Payment Method')}}:</label>
                <select name="payment_method_id" form="form-insert" id="payment_method_id">
                    @foreach ($paymentMethods as $paymentMethod)
                        <option value='{{ $paymentMethod->id }}' data-type="{{ $paymentMethod->type }}" {{ old('payment_method_id') == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
            </div>
            <div id="div_card" class="col_50 md_col">
                <label for="card_id">{{__('Card')}}:</label>
                <select name="card_id" form="form-insert" id="card_id" required>
                </select>
            </div>
        </div>

        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-base.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action="{{route('transaction-base.store')}}"> @csrf </form>
    </div>
@endsection
