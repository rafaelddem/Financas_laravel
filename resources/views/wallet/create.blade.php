@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('New wallet')}}</h1>
        {{__('Path to wallet', ['owner' => $owner->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action=" {{route('owner.wallet.store', ['owner_id' => $owner->id])}} ">
                    @csrf
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" placeholder="Nome" required>
                    <label for="nome">Carteira:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" name="main_wallet" value="1">Principal</label>
                        <label class="radio-option"><input type="radio" name="main_wallet" value="0" checked>Secundária</label>
                    </div>
                    <label for="nome">Status:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" name="active" value="1" checked>Ativo</label>
                        <label class="radio-option"><input type="radio" name="active" value="0">Inativo</label>
                    </div>
                    <input type="submit" value="Adicionar">
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $owner->id])}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
