@extends('layout')

@push('head-script')
    <script src="{{ asset('js/card/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Edit card")}}</h1>
        {{__('Path to card', ['owner' => $card->wallet->owner->name, 'wallet' => $card->wallet->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-edit" name="id" value="{{$card->id}}">
                <label>{{__('Name')}}:</label>
                <div class="row">
                    <label><strong>{{ $card->name }}</strong></label>
                </div>
                <label for="card_type">{{__('Type')}}:</label>
                @if($card->card_type == 'credit')
                    <div class="radio-container">
                        <label><strong>{{__('Credit')}}</strong></label>
                    </div>
                    <div class="row">
                        <div class="col_child">
                            <label for="first_day_month">{{__('First day of month')}}</label>
                            <input type="number" form="form-edit" name="first_day_month" id="first_day_month" value="1" min="1" max="28" required>
                        </div>
                        <div class="col_child">
                            <label for="days_to_expiration">{{__('Days to expiration')}}</label>
                            <input type="number" form="form-edit" name="days_to_expiration" id="days_to_expiration" value="10" min="1" max="20" required>
                        </div>
                    </div>
                @else
                    <div class="radio-container">
                        <label><strong>{{__('Debit')}}</strong></label>
                    </div>
                @endif
                @if($card->active)
                    <label>{{__('Status')}}:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" form="form-edit" name="active" value="1" checked>Ativo</label>
                        <label class="radio-option"><input type="radio" form="form-edit" name="active" value="0">Inativo</label>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-edit" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}'">
                <form method="post" id="form-edit" action="{{route('owner.wallet.card.update', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}"> @csrf @method('PUT') </form>
            </div>
        </div>
    </div>
@endsection
