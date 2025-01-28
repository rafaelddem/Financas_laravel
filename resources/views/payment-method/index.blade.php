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

<br />
<div class="container">
    <div class="row">
        <a href="{{ route('payment-method.create') }}" class="btn btn-primary">Novo</a>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                @foreach($paymentMethods as $paymentMethod)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $paymentMethod->name }}
                    @switch($paymentMethod->type)
                        @case('notes') ({{__('Notes')}}) @break
                        @case('transfer') ({{__('Transfer')}}) @break
                        @case('debit') ({{__('Debit')}}) @break
                        @case('credit') ({{__('Credit')}}) @break
                    @endswitch

                    <span class="d-flex">
                        <form method="post" action="{{route('payment-method.update')}}">
                            @csrf @method('PUT')
                            <input type="hidden" name="id" value={{$paymentMethod->id}}>
                            <input type="hidden" name="active" value={{!$paymentMethod->active}}>
                            <button type="submit" class="btn btn-primary">@if ($paymentMethod->active) Inativar @else Ativar @endif</button>
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
