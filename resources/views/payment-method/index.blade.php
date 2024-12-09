@extends('layout')

@section('header')
Método de Pagamento
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
            @if (isset($paymentMethod))
                @include('payment-method.edit', ['paymentMethod' => $paymentMethod])
            @else
                @include('payment-method.create')
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($paymentMethods as $paymentMethod)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $paymentMethod->name }}
                    @switch($paymentMethod->type)
                        @case(0) (Cédulas e/ou Moedas) @break
                        @case(1) (Transações Bancárias) @break
                        @case(2) (Débito) @break
                        @case(3) (Crédito) @break
                    @endswitch

                    <span class="d-flex">
                        <form method="get" action="{{route('payment-method.list', ['id' => $paymentMethod->id])}}">
                            <input type="hidden" name="id" value={{$paymentMethod->id}}>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <form method="post" action="{{route('payment-method.destroy')}}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" value={{$paymentMethod->id}}>
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
