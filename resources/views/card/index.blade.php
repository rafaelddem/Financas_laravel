@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Cards')}}</h1>
        {{__('Path to card', ['owner' => $wallet->owner->name, 'wallet' => $wallet->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('owner.wallet.card.create', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id]) }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="button" value="Voltar" onclick="window.location='{{app('url')->route('owner.wallet.list', ['owner_id' => $wallet->owner_id])}}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    @foreach($cards as $card)
                    <tr>
                        <td class="td-item">
                            <div class="td-content">
                                <span class="item-value">
                                    {{ $card->name }} 
                                    @if(!$card->active)
                                        <span class="tag">inativo</span>
                                    @endif
                                </span>
                            </div>
                            <div class="td-buttons">
                                <form method="get" action="{{route('owner.wallet.card.edit', ['owner_id' => $wallet->owner_id, 'wallet_id' => $wallet->id, 'id' => $card->id])}}">
                                    <button type="submit">Editar</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
