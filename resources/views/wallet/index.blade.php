@extends('layout')

@section('header')
{{__('Wallets from owner', ['owner' => $owner->name])}}
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
        <a href="{{ route('owner.wallet.create', ['owner_id' => $owner->id]) }}" class="btn btn-primary">Novo</a>
        <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.list')}}'">
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
                        @if(!$wallet->main_wallet)
                        <form method="get" action="{{route('owner.wallet.edit', ['owner_id' => $owner->id, 'id' => $wallet->id])}}">
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <form method="post" action="{{route('owner.wallet.destroy', ['owner_id' => $owner->id])}}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" value={{$wallet->id}}>
                            <button type="submit" class="btn btn-primary">Remover</button>
                        </form>
                        @endif
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
