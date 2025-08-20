@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Cards')}}</h1>
        {{__('Path to card', ['owner' => $wallet->owner->name, 'wallet' => $wallet->name])}}
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('owner.wallet.card.create', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id]) }}'">
                <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $wallet->owner_id])}}'">
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <table>
                    @foreach($cards as $card)
                    <tr>
                        <td class="td-item">
                            <div class="td-content">
                                {{ $card->name }} 
                                @if($card->card_type == 'credit')
                                    <span class="tag">{{__('Credit')}}</span>
                                @else
                                    <span class="tag">{{__('Debit')}}</span>
                                @endif
                                @if(!$card->active)
                                    <span class="tag">inativo</span>
                                @endif
                            </div>
                            <div class="td-buttons">
                                <form method="get" id="form-edit-{{$card->id}}" action="{{route('owner.wallet.card.edit', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id, 'id' => $card->id])}}"></form>
                                <button type="submit" form="form-edit-{{$card->id}}">{{__('Edit')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
