@extends('layout')

@section('header')
{{__('Transaction Type')}}
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
        <a href="{{ route('transaction-type.create') }}" class="btn btn-primary">Novo</a>
    </div>
    <br>
    <div class="row">
        <div class="col">
            <ul class="list-group">
                @foreach($transactionTypes as $transactionType)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $transactionType->name }}
                    @switch($transactionType->relevance)
                        @case('banal') ({{__('Banal')}}) @break
                        @case('relevant') ({{__('Relevant')}}) @break
                        @case('indispensable') ({{__('Indispensable')}}) @break
                    @endswitch

                    <span class="d-flex">
                        <form method="get" action="{{route('transaction-type.edit', ['id' => $transactionType->id])}}">
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
