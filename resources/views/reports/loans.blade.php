@extends('layout')

@push('head-script')
    <script src="{{ asset('js/charts.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{ __('Wallet Loans') }}</h1>
    </div>
    <div class="presentation">
        <form method="get" id="form-loans" action="{{ route('reports.loans') }}" enctype="multipart/form-data">
            <h2 class="card-title">{{__('Filters')}}</h2>
            <div class="flex-container">
                <div class="col_25 md_col_50 sm_col">
                    <label for="owner_id">{{ __('Wallet Loans By Owner') }}:</label>
                    @if($owners->count() > 0)
                        <select name="owner_id" form="form-loans">
                            @foreach($owners as $owner)
                                <option value="{{$owner->id}}" @if($owner->id == $owner_id) selected @endif>{{$owner->name}}</option>
                            @endforeach
                        </select>
                    @else
                        <select name="owner_id" disabled>
                            <option value="" selected hidden>{{ __('There Are No Owners') }}</option>
                        </select>
                    @endif
                </div>
                <div class="col_25">
                    <label for="title">{{__('Start Date')}}:</label>
                    <input type="date" form="form-loans" name="start_date" id="start_date" value="{{$start_date->format('Y-m-d')}}" required>
                </div>
                <div class="col_25">
                    <label for="title">{{__('End Date')}}:</label>
                    <input type="date" form="form-loans" name="end_date" id="end_date" value="{{$end_date->format('Y-m-d')}}" required>
                </div>
                <div class="col_25 md_col_50 sm_col">
                    <input class="button-as-input" type="submit" form="form-loans" value="{{ __('Filter') }}" @if($owners->count() == 0) disabled @endif>
                </div>
            </div>
        </form>
    </div>
    
    <div class="presentation">
        <h2 class="card-title">{{__('Transactions')}}</h2>
        @php($in = $out = $parcial = 0)
        @if($ownerLoans['beforePeriod'] != 0)
            @php($parcial = $ownerLoans['beforePeriod'])
            <div class="flex-container">
                <div class="col_15"></div>
                <div class="col_30"></div>
                <div class="col_25"><b>{{__('Amounts Before Period')}}: </b></div>
                <div class="col_15"></div>
                <div class="col_15"><b>{{ \App\Helpers\MoneyHelper::format($ownerLoans['beforePeriod'] ?? 0) }}</b></div>
            </div>
        @endif
        @foreach($ownerLoans['fromPeriod'] as $month => $transactionsByMonth)
            <div class="separator-with-text"><span>{{__('Period :reference', ['reference' => $month])}}</span></div>
            @foreach($transactionsByMonth as $transaction)
                <div class="flex-container">
                    <div class="col_15">
                        {{$transaction['date']->format('d/m/Y')}}
                    </div>
                    <div class="col_25">
                        {{$transaction['source_owner_name']}} > {{$transaction['destination_owner_name']}} 
                        | <span class="tag">{{__(\App\Enums\PaymentType::translate($transaction['payment_methods_type']))}}</span>
                    </div>
                    <div class="col_30">
                        {{$transaction['transactions_title']}}
                    </div>
                    <div class="col_15">
                        @if ($transaction['source_owner_id'] == env('MY_OWNER_ID'))
                            @php($in += $transaction['net_value'])
                            {{ \App\Helpers\MoneyHelper::format($transaction['net_value']) }}
                        @endif
                    </div>
                    <div class="col_15">
                        @if ($transaction['source_owner_id'] != env('MY_OWNER_ID'))
                            @php($out += $transaction['net_value'])
                            {{ \App\Helpers\MoneyHelper::format($transaction['net_value']) }}

                            @if ($transaction['source_owner_id'] != env('MY_OWNER_ID') && $transaction['destination_owner_id'] != env('MY_OWNER_ID')) * @endif
                        @endif
                    </div>
                </div>
            @endforeach
            @php($parcial += ($in - $out))
            <div class="flex-container">
                <div class="col_15"></div>
                <div class="col_30"></div>
                <div class="col_25"></div>
                <div class="col_15"><b>{{__('Amount Until End Date')}}</b></div>
                <div class="col_15"><b>{{ \App\Helpers\MoneyHelper::format($parcial) }}</b></div>
            </div>
            @php($in = $out = 0)
        @endforeach
        <br>
        <p>{{__('Observations')}}:</p>
        <p>* {{__('Value correction movement. It will not necessarily be displayed in the corresponding column. Pay attention to the source and destination wallets for better understanding.')}}</p>
        <p>** {{__('Regarding the totals, when the value shown is positive, it means I should receive that amount, and when it is negative, it means I should return it.')}}</p>
    </div>
@endsection
