@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Edit card name", ["card" => $card->name])}}</h1>
        {{__('Path to card', ['owner' => $card->wallet->owner->name, 'wallet' => $card->wallet->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action="{{route('owner.wallet.card.update', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}">
                    @csrf @method('PUT')
                    <input type="hidden" name="id" value="{{$card->id}}">
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" id="name" value="{{ $card->name }}" disabled>
                    <div class="row">
                        <div class="col">
                        @if($card->card_type == 'credit')
                            <label><strong>{{__('Credit')}}</strong></label>
                        @else
                            <label><strong>{{__('Debit')}}</strong></label>
                        @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="first_day_month">{{__('First day of month')}}</label>
                            <input type="number" class="form-control" name="first_day_month" id="first_day_month" value="1" min="1" max="28" required>
                        </div>
                        <div class="col">
                            <label for="days_to_expiration">{{__('Days to expiration')}}</label>
                            <input type="number" class="form-control" name="days_to_expiration" id="days_to_expiration" value="10" min="1" max="20" required>
                        </div>
                    </div>

                    @if($card->active)
                        <label>Status:</label>
                        <div class="radio-container">
                            <label class="radio-option"><input type="radio" name="active" value="1" checked>Ativo</label>
                            <label class="radio-option"><input type="radio" name="active" value="0">Inativo</label>
                        </div>
                        <input type="submit" value="Atualizar">
                    @endif
                    <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.card.list', ['owner_id' => $card->wallet->owner->id, 'wallet_id' => $card->wallet->id])}}'">
                </form>
            </div>
        </div>
    </div>
@endsection
