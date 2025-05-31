@extends('layout')

@section('path')
{{__('Path to card', ['owner' => $card->wallet->owner->name, 'wallet' => $card->wallet->name])}}
@endsection

@section('header')
{{__('Card from wallet', ['wallet' => $card->wallet->name])}}
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
    </div>
@endif
@if(!empty($message))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<div class="container">
    <div class="row">
        <div class="col">
            <form method="post" action=" {{route('owner.wallet.card.update', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}} ">
                @csrf @method('PUT')
                    <div class="container">
                    <input type="hidden" name="id" value="{{$card->id}}">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" value="{{$card->name}}" disabled>
                    <br />
                    @if($card->card_type == 'credit')
                        <label>{{__('Credit')}}</label><br />
                    @else
                        <label>{{__('Debit')}}</label><br />
                    @endif
                    <br />
                    <label for="first_day_month">{{__('First day of month')}}</label>
                    <input type="number" class="form-control" name="first_day_month" id="first_day_month" value="{{$card->first_day_month}}" min="1" max="28" @if ($card->active) required @else disabled @endif>
                    <br />
                    <label for="days_to_expiration">{{__('Days to expiration')}}</label>
                    <input type="number" class="form-control" name="days_to_expiration" id="days_to_expiration" value="{{$card->days_to_expiration}}" min="1" max="20" @if ($card->active) required @else disabled @endif>
                    <br />
                    @if($card->active)
                        <label for="active">Ativo</label>
                        <input type="hidden" name="active" value=false>
                        <input type="checkbox" name="active" id="active" value=1 checked>
                        <br />
                        <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    @endif
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
