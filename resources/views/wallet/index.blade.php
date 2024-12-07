@extends('layout')

@section('header')
Carteira
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

<br />
<div class="container">
    <div class="row">
        <div class="col">
            @if (isset($wallet))
                @include('wallet.edit', ['wallet' => $wallet, 'owners' => $owners])
            @else
                @include('wallet.create', ['owners' => $owners])
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($wallets as $wallet)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $wallet->name }} - {{ $wallet->owner->name }}
                    @if(!$wallet->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='{{route('wallet.list', ['id' => $wallet->id])}}';">
                        <form method="post" action="{{route('wallet.update')}}">
                            @csrf @method('PUT')
                            <input type="hidden" name="id" value={{$wallet->id}}>
                            <input type="hidden" name="active" @if ($wallet->active) value="0" @else value="1" @endif>
                            @if (!$wallet->main_wallet)
                                <input class="btn btn-primary" type="submit" @if ($wallet->active) value="Inativar" @else value="Ativar" @endif >
                            @endif
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
