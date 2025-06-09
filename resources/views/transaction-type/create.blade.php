@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action=" {{route('transaction-type.store')}} ">
                    @csrf
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" placeholder="Nome" required>
                    <label for="relevance">Relevância:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
                        <option value='banal'>{{__('Banal')}}</option>
                        <option value='relevant'>{{__('Relevant')}}</option>
                        <option value='indispensable'>{{__('Indispensable')}}</option>
                    </select>
                    <label for="nome">Status:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" name="active" value="1" checked>Ativo</label>
                        <label class="radio-option"><input type="radio" name="active" value="0">Inativo</label>
                    </div>
                    <input type="submit" value="Adicionar">
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
