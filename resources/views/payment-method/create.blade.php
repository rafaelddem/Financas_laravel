@extends('layout')

@section('header')
{{__('Payment Method')}}
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
            <form method="post" action=" {{route('payment-method.store')}} ">
                @csrf
                <input type="hidden" name="active" value=true>
                <div class="container">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="type">Tipo:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="type" id="type">
                        <option value="notes">{{__('Notes')}}</option>
                        <option value="transfer">{{__('Transfer')}}</option>
                        <option value="debit">{{__('Debit')}}</option>
                        <option value="credit">{{__('Credit')}}</option>
                    </select>
                    <br />
                    <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('payment-method.list')}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
