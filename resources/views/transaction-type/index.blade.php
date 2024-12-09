@extends('layout')

@section('header')
Tipo de Transação
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
            @if (isset($transactionType))
                @include('transaction-type.edit', ['transactionType' => $transactionType])
            @else
                @include('transaction-type.create')
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($transactionTypes as $transactionType)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $transactionType->name }}
                    @switch($transactionType->relevance)
                        @case(0) (Banal) @break
                        @case(1) (Relevante) @break
                        @case(2) (Importante) @break
                    @endswitch

                    <span class="d-flex">
                        <form method="get" action="{{route('transaction-type.list', ['id' => $transactionType->id])}}">
                            <input type="hidden" name="id" value={{$transactionType->id}}>
                            <button type="submit" class="btn btn-primary">Editar</button>
                        </form>
                        <form method="post" action="{{route('transaction-type.destroy')}}">
                            @csrf @method('DELETE')
                            <input type="hidden" name="id" value={{$transactionType->id}}>
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
