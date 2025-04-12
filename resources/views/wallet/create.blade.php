@extends('layout')

@section('path')
{{__('Path to wallet', ['owner' => $owner->name])}}
@endsection

@section('header')
{{__('New wallet')}}
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
            <form method="post" action=" {{route('owner.wallet.store', ['owner_id' => $owner->id])}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="main_wallet">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" value=1>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" value=1 checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $owner->id])}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
