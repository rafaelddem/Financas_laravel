@extends('layout')

@push('head-script')
    <script src="{{ asset('js/wallet/script.js') }}" defer></script>
@endpush('head-script')

@section('page_content')
    <div class="presentation">
        <h1>{{__('New wallet')}}</h1>
        {{__('Path to wallet', ['owner' => $owner->name])}}
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="{{__('Name')}}" value="{{old('name')}}" required>
                <label>{{__('Wallet')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-insert" name="main_wallet" id="main_wallet" value="1">{{__('Main Wallet')}}</label>
                    <label class="radio-option"><input type="radio" form="form-insert" name="main_wallet" id="sec_wallet" value="0" checked>{{__('Secondary Wallet')}}</label>
                </div>
                <label>{{__('Wallet Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option" id="active_label"><input type="radio" form="form-insert" name="active" id="active" value="1" checked>{{__('Active')}}</label>
                    <label class="radio-option" id="inactive_label"><input type="radio" form="form-insert" name="active" id="inactive" value="0">{{__('Inactive')}}</label>
                </div>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $owner->id])}}'">
                <form method="post" id="form-insert" action=" {{route('owner.wallet.store', ['owner_id' => $owner->id])}} "> @csrf </form>
            </div>
        </div>
    </div>
@endsection
