@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Edit wallet name', ['wallet' => $wallet->name])}}</h1>
        {{__('Path to wallet', ['owner' => $wallet->owner->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action="{{route('owner.wallet.update', ['owner_id' => $wallet->owner_id])}}">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" value={{$wallet->id}}>
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" id="name" value="{{ $wallet->name }}" disabled>
                    @if ($wallet->main_wallet)
                        <label for="nome">Carteira Principal</label>
                    @else
                        <label for="nome">Carteira:</label>
                        <div class="radio-container">
                            <label class="radio-option"><input type="radio" name="main_wallet" value="1">Principal</label>
                            <label class="radio-option"><input type="radio" name="main_wallet" value="0" checked>Secundária</label>
                        </div>
                        <label for="nome">Status:</label>
                        <div class="radio-container">
                            <label class="radio-option"><input type="radio" name="active" value="1" @if ($wallet->active) checked @endif>Ativo</label>
                            <label class="radio-option"><input type="radio" name="active" value="0" @if (!$wallet->active) checked @endif>Inativo</label>
                        </div>
                    @endif
                    <input type="submit" value="Atualizar">
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $wallet->owner_id])}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
