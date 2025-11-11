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
                <div class="col_25"><b>Total anterior ao período: </b></div>
                <div class="col_15"></div>
                <div class="col_15"><b>{{ \App\Helpers\MoneyHelper::format($ownerLoans['beforePeriod'] ?? 0) }}</b></div>
            </div>
        @endif
        @foreach($ownerLoans['fromPeriod'] as $month => $transactionsByMonth)
            <div class="separator-with-text"><span> Período: {{$month}} </span></div>
            @foreach($transactionsByMonth as $transaction)
                <div class="flex-container">
                    <div class="col_15">
                        {{$transaction->processing_date->format('d/m/Y')}}
                    </div>
                    <div class="col_25">
                        {{$transaction->sourceWallet->owner->name}} > {{$transaction->destinationWallet->owner->name}}
                    </div>
                    <div class="col_30">
                        {{$transaction->title}}
                    </div>
                    <div class="col_15">
                        @if ($transaction->sourceWallet->owner_id == env('MY_OWNER_ID'))
                            @php($in += $transaction->net_value)
                            {{$transaction->net_value_formatted}}
                        @endif
                    </div>
                    <div class="col_15">
                        @if ($transaction->sourceWallet->owner_id != env('MY_OWNER_ID'))
                            @php($out += $transaction->net_value)
                            {{$transaction->net_value_formatted}}
                        @endif
                    </div>
                </div>
            @endforeach
            <div class="flex-container">
                <div class="col_15"></div>
                <div class="col_30"></div>
                <div class="col_25"></div>
                <div class="col_15"> Total do período: </div>
                <div class="col_15">{{ \App\Helpers\MoneyHelper::format($in - $out) }}</div>
            </div>
            @php($parcial = $in - $out + $parcial)
            @php($in = $out = 0)
        @endforeach

        <div class="flex-container">
            <div class="col_15"></div>
            <div class="col_30"></div>
            <div class="col_25"><b>{{__('Total')}}</b></div>
            <div class="col_15"></div>
            <div class="col_15"><b>{{ \App\Helpers\MoneyHelper::format($parcial) }}</b></div>
        </div>
    </div>
@endsection
