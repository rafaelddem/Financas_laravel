@php
use App\Enums\Relevance;
@endphp

@extends('layout')

@push('head-script')
    <script src="{{ asset('js/extract-import/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Extract Import')}}</h1>
    </div>
    <div class="presentation">
        <form method="post" id="form-extract" action="{{ route('extract-import.extract') }}" enctype="multipart/form-data">
            @csrf
            <h2 class="card-title">{{__('Extract Import')}}</h2>
            <div class="flex-container">
                <div class="col_25 md_col_50 sm_col">
                    <input class="button-as-input" type="submit" id="submit_button" value="{{ __('Extract') }}" disabled>
                </div>
                <div class="col_25 md_col_50 sm_col">
                    <label for="module_id">{{ __('Extract Module') }}:</label>
                    <select name="module_id" id="module_id">
                        <option value="" selected hidden>{{ __('Select A Extract Module') }}</option>
                        @foreach ($importOptions as $importOption)
                            <option value="{{ $importOption->id }}">{{ $importOption->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col_50 md_col">
                    <label for="extract_file">{{ __('Source Wallet') }}:</label>
                    <input type="file" accept=".txt,.csv,.xlsx" name="extract_file" id="extract_file" disabled />
                </div>
            </div>
        </form>
    </div>

    @if($filesToImport->count() > 0)
    <div class="presentation">
        <h2 class="card-title">{{__('Extract Approval')}}</h2>
        @foreach($filesToImport as $key => $file)
        <div class="flex-container">
            <input type="hidden" form="form-import-{{$key}}" name="file_name" value="{{$file->file_name}}">
            <div class="col_25 md_col_50 md_col">
                <h4><label id="installmentData" class="label-as-input">{{$file->file_name}}</label></h4>
            </div>
            <div class="col_25 md_col_50 sm_col">
                @php
                    $disabled = $file->transactions_left > 0 ? 'disabled' : '';
                    $label = $file->transactions_left == 0
                        ? __('Ready')
                        : trans_choice('Files Left', $file->transactions_left, ['count' => $file->transactions_left]);
                @endphp
                <input class="button-as-input" type="submit" form="form-import-{{$key}}" value="{{ $label }}" {{ $disabled }}>
            </div>
            <form method="post" id="form-import-{{$key}}" action="{{route('extract-import.import')}}"> @csrf </form>
        </div>
        @endforeach
    </div>
    @endif

    @if($importTransactions->count() > 0)
    <div class="presentation">
        <h2 class="card-title">{{__('Extract Approval')}}</h2>
        @foreach($importTransactions as $key => $importTransaction)
        <div class="separator-with-text"><span>Informações pessoais</span></div>
        <div class="flex-container">
            <input type="hidden" form="form-approve-{{$key}}" name="id" value="{{$importTransaction->id}}">
            <div class="col_25 md_col">
                <label for="title">{{__('Title')}}:</label>
                <input type="text" form="form-approve-{{$key}}" name="title" id="transaction[{{$key}}][title]" value="{{$importTransaction->title}}" required>
            </div>
            <div class="col_25 md_col_33 sm_col">
                <input type="hidden" form="form-approve-{{$key}}" name="transaction_date" value="{{$importTransaction->transaction_date->format('Y-m-d')}}">
                <label for="transaction_date">{{__('Transaction/Proces. Date')}}:</label>
                <input type="date" form="form-approve-{{$key}}" value="{{$importTransaction->transaction_date->format('Y-m-d')}}" disabled>
            </div>
            <div class="col_25 md_col_33 sm_col">
                <label for="category_id">{{__('Category')}}:</label>
                <select form="form-approve-{{$key}}" name="category_id" id="transaction[{{$key}}][category_id]">
                    @foreach ($categories as $presentation)
                        <option value='{{ $presentation->id }}' data-relevance="{{ $presentation->relevance }}" {{ $importTransaction->category_id == $presentation->id ? 'selected' : '' }}>{{ $presentation->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 sm_col">
                <label for="relevance">{{__('Relevance')}}:</label>
                <select form="form-approve-{{$key}}" name="relevance" id="transaction[{{$key}}][relevance]">
                    @foreach (Relevance::cases() as $relevance)
                        <option value='{{ $relevance->value }}' {{ $importTransaction->category->relevance->value == $relevance->value ? 'selected' : '' }}>{{ __($relevance->name) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 sm_col">
                <label for="payment_method_id">{{__('Payment Method')}}:</label>
                <select form="form-approve-{{$key}}" data-key="{{$key}}" name="payment_method_id" id="transaction[{{$key}}][payment_method_id]">
                    @foreach ($paymentMethods as $paymentMethod)
                        <option value='{{ $paymentMethod->id }}' data-type="{{ $paymentMethod->type }}" {{ $importTransaction->payment_method_id == $paymentMethod->id ? 'selected' : '' }}>{{ $paymentMethod->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <label for="source_wallet_id">{{__('Source Wallet')}}:</label>
                <select form="form-approve-{{$key}}" data-key="{{$key}}" name="source_wallet_id" id="transaction[{{$key}}][source_wallet_id]">
                    @foreach ($sourceWallets as $sourceWallet)
                        <option value='{{ $sourceWallet->id }}' data-owner="{{ $sourceWallet->owner_id }}" data-wallet="{{ $sourceWallet->id }}" {{ $importTransaction->source_wallet_id == $sourceWallet->id ? 'selected' : '' }}>
                            {{ $sourceWallet->owner->name }} > {{ $sourceWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col_25 md_col_50 sm_col">
                <label for="destination_wallet_id">{{__('Destination Wallet')}}:</label>
                <select form="form-approve-{{$key}}" name="destination_wallet_id" id="transaction[{{$key}}][destination_wallet_id]">
                    @foreach ($destinationWallets as $destinationWallet)
                        <option value='{{ $destinationWallet->id }}' {{ $importTransaction->destination_wallet_id == $destinationWallet->id ? 'selected' : '' }}>
                            {{ $destinationWallet->owner->name }} > {{ $destinationWallet->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div id="div_card[{{$key}}]" class="col_25 md_col_50 sm_col">
                <label for="card_id">{{__('Card')}}:</label>
                <select name="card_id" form="form-approve-{{$key}}" id="card_id[{{$key}}]" data-selected="{{old('card_id')}}" required>
                </select>
            </div>
            <div class="col_25 md_col">
                <label for="gross_value">{{__('Gross Value')}}:</label>
                <input type="text" form="form-approve-{{$key}}" class="money" name="gross_value" id="transaction[{{$key}}][gross_value]" value="{{$importTransaction->gross_value ?? '0,00'}}" required pattern="^(?!0,00$).+" title="{{__('A value needs to be entered')}}">
            </div>
            <div class="col_25 md_col_50 sm_col">
                <input class="button-as-input" type="submit" form="form-approve-{{$key}}" value="{{ __('Ready') }}">
            </div>
            <form method="post" id="form-approve-{{$key}}" action="{{route('extract-import.ready')}}"> @csrf </form>
        </div>
        @endforeach
    </div>
    @endif
@endsection
