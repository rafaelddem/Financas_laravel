@extends('layout')

@push('head-script')
    <script src="{{ asset('js/card/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('New card')}}</h1>
        {{__('Path to card', ['owner' => $wallet->owner->name, 'wallet' => $wallet->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="Nome" required>
                <label for="card_type">{{__('Type')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-insert" name="card_type" id="card_type_credit" value="credit" checked>{{ __('Credit') }}</label>
                    <label class="radio-option"><input type="radio" form="form-insert" name="card_type" id="card_type_debit" value="debit">{{ __('Debit') }}</label>
                </div>
                <div class="row">
                    <div class="col_child">
                        <label for="first_day_month" id="first_day_month_label">{{__('First day of month')}}</label>
                        <input type="number" form="form-insert" name="first_day_month" id="first_day_month" value="1" min="1" max="28" required>
                    </div>
                    <div class="col_child">
                        <label for="days_to_expiration" id="days_to_expiration_label">{{__('Days to expiration')}}</label>
                        <input type="number" form="form-insert" name="days_to_expiration" id="days_to_expiration" value="10" min="1" max="20" required>
                    </div>
                </div>
                <label>{{__('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="1" checked>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="0">{{__('Inactive')}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id])}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action=" {{route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id])}} "> @csrf </form>
    </div>
@endsection
