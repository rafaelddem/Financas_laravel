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
        <div class="col">
            <form method="post" action="{{route('wallet.update')}}">
                @csrf @method('PUT')
                <div class="container">
                    <input type="hidden" name="id" value={{$wallet->id}}>
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $wallet->name }}" disabled>
                    <br />
                    <label for="owner_id">Pertencente a:</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $wallet->owner->name }}" disabled>
                    <br />
                    <label for="nome">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" value=1 @if ($wallet->main_wallet) checked @endif @if ($wallet->main_wallet) disabled @endif >
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="active" id="active" value=1 @if ($wallet->active) checked @endif @if ($wallet->main_wallet) disabled @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->previous()}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
