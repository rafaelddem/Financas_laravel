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
            <form method="post" action=" {{route('transaction-type.store')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="relevance">Relevância:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
                        <option value='banal'>{{__('Banal')}}</option>
                        <option value='relevant'>{{__('Relevant')}}</option>
                        <option value='indispensable'>{{__('Indispensable')}}</option>
                    </select>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="hidden" name="active" value=false>
                    <input type="checkbox" name="active" value=true checked>
                    <br />
                    <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
