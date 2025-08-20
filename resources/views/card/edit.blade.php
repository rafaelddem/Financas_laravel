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
        <div class="flex-container">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-edit" name="id" value="{{$card->id}}">
                <label>{{__('Name')}}:</label>
                <div class="flex-container">
                    <label><strong>{{ $card->name }}</strong></label>
                </div>
            </div>
        </div>
        @if($card->card_type == 'credit')
            <div class="flex-container">
                <div class="col_25">
                    <label for="card_type">{{__('Type')}}:</label>
                    <div class="flex-container">
                        <label><strong>{{__('Credit')}}</strong></label>
                    </div>
                </div>
            </div>
            @if($card->active)
                <div class="flex-container">
                    <div class="col_25">
                        <label for="first_day_month" id="first_day_month_label">{{__('First day of month')}}</label>
                        <input type="number" form="form-edit" name="first_day_month" id="first_day_month" value="{{$card->first_day_month}}" min="1" max="28" required>
                    </div>
                    <div class="col_25">
                        <label for="days_to_expiration" id="days_to_expiration_label">{{__('Days to expiration')}}</label>
                        <input type="number" form="form-edit" name="days_to_expiration" id="days_to_expiration" value="{{$card->days_to_expiration}}" min="1" max="20" required>
                    </div>
                </div>
            @else
                <div class="flex-container">
                    <div class="col_25">
                        <label for="first_day_month" id="first_day_month_label">{{__('First day of month')}}</label>
                        <div class="flex-container">
                            <label><strong>{{$card->first_day_month}}</strong></label>
                        </div>
                    </div>
                    <div class="col_25">
                        <label for="days_to_expiration" id="days_to_expiration_label">{{__('Days to expiration')}}</label>
                        <div class="flex-container">
                            <label><strong>{{$card->days_to_expiration}}</strong></label>
                        </div>
                    </div>
                </div>
                <div class="flex-container"></div>
            @endif
        @else
            <div class="flex-container">
                <div class="col_25">
                    <label for="card_type">{{__('Type')}}:</label>
                    <div class="flex-container">
                        <label><strong>{{__('Debit')}}</strong></label>
                    </div>
                </div>
            </div>
        @endif
        @if($card->active)
            <div class="flex-container">
                <div class="col_25">
                    <label>{{__('Status')}}:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" form="form-edit" name="active" value="1" checked>{{__('Active')}}</label>
                        <label class="radio-option"><input type="radio" form="form-edit" name="active" value="0">{{__('Inactive')}}</label>
                    </div>
                </div>
            </div>
        @endif
        <div class="flex-container">
            <div class="col">
                @if($card->active)
                    <input type="submit" form="form-edit" value="{{__('Save')}}">
                    <form method="post" id="form-edit" action="{{route('owner.wallet.card.update', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}"> @csrf @method('PUT') </form>
                @endif
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}'">
            </div>
        </div>
    </div>
@endsection
