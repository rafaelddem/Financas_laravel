@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action="{{route('transaction-type.update')}}">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" value={{$transactionType->id}}>
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" id="name" value="{{ $transactionType->name }}" disabled>
                    <label for="relevance">Relevância:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
                        <option value='banal' @if ($transactionType->relevance == 'banal') selected @endif>{{__('Banal')}}</option>
                        <option value='relevant' @if ($transactionType->relevance == 'relevant') selected @endif>{{__('Relevant')}}</option>
                        <option value='indispensable' @if ($transactionType->relevance == 'indispensable') selected @endif>{{__('Indispensable')}}</option>
                    </select>
                    <label for="nome">Status:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" name="active" value="1" @if ($transactionType->active) checked @endif>Ativo</label>
                        <label class="radio-option"><input type="radio" name="active" value="0" @if (!$transactionType->active) checked @endif>Inativo</label>
                    </div>
                    <input type="submit" value="Atualizar">
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
