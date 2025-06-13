@extends('layout')

@push('head-script')
    <script src="{{ asset('js/wallet/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Edit wallet')}}</h1>
        {{__('Path to wallet', ['owner' => $wallet->owner->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-edit" name="id" value={{$wallet->id}}>
                <label>{{__('Name')}}:</label>
                <div class="row">
                    <label><strong>{{ $wallet->name }}</strong></label>
                </div>
                @if ($wallet->main_wallet)
                    <label>Carteira Principal</label>
                @else
                    <label>Carteira:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" form="form-edit" name="main_wallet" id="main_wallet" value="1">{{__('Main Wallet')}}</label>
                        <label class="radio-option"><input type="radio" form="form-edit" name="main_wallet" id="sec_wallet" value="0" checked>{{__('Secondary Wallet')}}</label>
                    </div>
                    <label>Status:</label>
                    <div class="radio-container">
                        <label class="radio-option" id="active_label"><input type="radio" form="form-edit" name="active" id="active" value="1" @if ($wallet->active) checked @endif>{{__('Active')}}</label>
                        <label class="radio-option" id="inactive_label"><input type="radio" form="form-edit" name="active" id="inactive" value="0" @if (!$wallet->active) checked @endif>{{__('Inactivate')}}</label>
                    </div>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-edit" value="{{__('Edit')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $wallet->owner_id])}}'">
            </div>
        </div>
        <form method="post" id="form-edit" action="{{route('owner.wallet.update', ['owner_id' => $wallet->owner_id])}}"> @csrf @method('PUT') </form>
    </div>
@endsection
