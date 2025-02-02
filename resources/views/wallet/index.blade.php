@extends('layout')

@section('header')
{{__('Wallet')}}
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
        <a href="{{ route('wallet.create') }}" class="btn btn-primary">Novo</a>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                @foreach($wallets as $wallet)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $wallet->owner->name }} - {{ $wallet->name }}
                    @if(!$wallet->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <form method="post" action="{{route('wallet.edit')}}">
                            @csrf
                            <input type="hidden" name="id" value={{$wallet->id}}>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <form method="post" action="{{route('wallet.destroy')}}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" value={{$wallet->id}}>
                            <button type="submit" class="btn btn-primary">Remover</button>
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
