@extends('layout')

@section('header')
Forma Pagamento
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} <br />
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
            @if (isset($paymentMethod))
            <form method="post" action="{{route('updatePaymentMethod', $paymentMethod->id)}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $paymentMethod->name }}">
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" @if ($paymentMethod->active) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{route('listPaymentMethod')}}';">
                </div>
            </form>
            @else
            <form method="post" action=" {{route('createPaymentMethod')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
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
                @foreach($paymentMethods as $paymentMethod)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $paymentMethod->name }}
                    @if(!$paymentMethod->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='{{route('findPaymentMethod', $paymentMethod->id)}}';">
                        <form method="post" action="{{route('deletePaymentMethod', $paymentMethod->id)}}"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($paymentMethod->name) }}?')">
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