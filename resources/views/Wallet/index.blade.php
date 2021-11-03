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
            <form method="post" action="{{route('updateWallet', $wallet->id)}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $wallet->name }}">
                    <br />
                    <label for="owner">Pertencente a:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="owner" id="owner">
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}" @if ($wallet->owner == $owner->id) selected @endif>{{ $owner->name }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="nome">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" @if ($wallet->main_wallet) checked @endif >
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="active" id="active" @if ($wallet->active) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{route('listWallet')}}';">
                </div>
            </form>
            @else
            <form method="post" action=" {{route('createWallet')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="owner">Pertencente a:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="owner" id="owner">
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="main_wallet">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" >
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
                </div>
            </form>
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($wallets as $wallet)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $wallet->name }} - {{ $wallet->owner()->getResults()->name }}
                    @if(!$wallet->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='{{route('findWallet', $wallet->id)}}';">
                        <form method="post" action="{{route('deleteWallet', $wallet->id)}}"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($wallet->nome) }}?')">
                            @csrf
                            <input class="btn btn-primary" type="submit" value="Excluir" style="background-color: red;border-color: red;">
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection