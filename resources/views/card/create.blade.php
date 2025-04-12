@extends('layout')

@section('header')
{{__('Cards from wallet', ['wallet' => $wallet->name])}}
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
            <form method="post" action=" {{route('owner.wallet.card.store', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id])}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" required>
                    <br />
                    <input type="radio" id="credit" name="card_type" value="credit" checked>
                    <label for="html">{{ __('Credit') }}</label>
                    <input type="radio" id="debit" name="card_type" value="debit">
                    <label for="css">{{ __('Debit') }}</label><br>
                    <br />
                    <label for="first_day_month">{{__('First day of month')}}</label>
                    <input type="number" class="form-control" name="first_day_month" id="first_day_month" value="1" min="1" max="28" required>
                    <br />
                    <label for="days_to_expiration">{{__('Days to expiration')}}</label>
                    <input type="number" class="form-control" name="days_to_expiration" id="days_to_expiration" value="10" min="1" max="20" required>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" value=1 checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $wallet->owner->id, 'wallet_id' => $wallet->id])}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
