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

<br />
<div class="container">
    <div class="row">
        <div class="col">
            <form method="post" action="{{route('transaction-type.update')}}">
                @csrf @method('PUT')
                <div class="container">
                    <input type="hidden" name="id" value={{$transactionType->id}}>
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" value="{{$transactionType->name}}" disabled>
                    <br />
                    <label for="relevance">Relevância:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
                        <option value='banal' @if ($transactionType->relevance == 'banal') selected @endif>{{__('Banal')}}</option>
                        <option value='relevant' @if ($transactionType->relevance == 'relevant') selected @endif>{{__('Relevant')}}</option>
                        <option value='indispensable' @if ($transactionType->relevance == 'indispensable') selected @endif>{{__('Indispensable')}}</option>
                    </select>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="hidden" name="active" value=false>
                    <input type="checkbox" name="active" value=true @if ($transactionType->active) checked @endif>
                    <br />
                    <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
