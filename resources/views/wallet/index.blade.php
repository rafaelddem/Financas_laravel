@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Wallets')}}</h1>
        {{__('Path to wallet', ['owner' => $owner->name])}}
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('owner.wallet.create', ['owner_id' => $owner->id]) }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="button" value="Voltar" onclick="window.location='{{ app('url')->route('owner.list') }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    @foreach($wallets as $wallet)
                    <tr>
                        <td class="td-item">
                            <div class="td-content">
                                <span class="item-value">
                                    {{ $wallet->owner->name }} - {{ $wallet->name }} 
                                    @if($wallet->main_wallet)
                                        <span class="tag">principal</span>
                                    @endif
                                    @if(!$wallet->active)
                                        <span class="tag">inativo</span>
                                    @endif
                                </span>
                            </div>
                            <div class="td-buttons">
                                <form method="get" action="{{route('owner.wallet.card.list', ['owner_id' => $owner->id, 'wallet_id' => $wallet->id, 'id' => $wallet->id])}}">
                                    <button type="submit">{{__('Cards')}}</button>
                                </form>
                                @if(!$wallet->main_wallet)
                                    <form method="get" action="{{route('owner.wallet.edit', ['owner_id' => $owner->id, 'id' => $wallet->id])}}">
                                        <button type="submit">Editar</button>
                                    </form>
                                    <form method="post" action="{{route('owner.wallet.destroy', ['owner_id' => $owner->id])}}">
                                        @csrf @method('DELETE')
                                        <input type="hidden" name="id" value={{$wallet->id}}>
                                        <button type="submit">Remover</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
