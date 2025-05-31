@extends('layout')

@section('path')
{{__('Path to card', ['owner' => $wallet->owner->name, 'wallet' => $wallet->name])}}
@endsection

@section('header')
{{__('Cards')}}
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
        <a href="{{ route('owner.wallet.card.create', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id]) }}" class="btn btn-primary">Novo</a>
        <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $wallet->owner_id])}}'">
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                @foreach($cards as $card)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $card->name }} @if(!$card->active) (inativo) @endif
                    <span class="d-flex">
                        <form method="get" action="{{route('owner.wallet.card.edit', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id, 'id' => $card->id])}}">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
