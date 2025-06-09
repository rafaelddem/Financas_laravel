@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{"Payment Method"}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action=" {{route('payment-method.store')}} ">
                    @csrf
                    <input type="hidden" name="active" value=true>
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" placeholder="Nome" required>
                    <label for="type">Tipo:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="type" id="type">
                        <option value="notes">{{__('Notes')}}</option>
                        <option value="transfer">{{__('Transfer')}}</option>
                        <option value="debit">{{__('Debit')}}</option>
                        <option value="credit">{{__('Credit')}}</option>
                    </select>
                    <input type="submit" value="Adicionar">
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('payment-method.list')}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
