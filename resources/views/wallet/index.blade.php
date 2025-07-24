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
                <input type="button" value="Voltar" onclick="window.location='{{ app('url')->route('owner.list') }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    @foreach($wallets as $wallet)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $wallet->owner->name }} - {{ $wallet->name }} 
                                @if($wallet->main_wallet)
                                    <span class="tag">{{__('Main Wallet')}}</span>
                                @endif
                                @if(!$wallet->active)
                                    <span class="tag">{{__('Inactive')}}</span>
                                @endif
                            </span>
                            <div class="td-buttons">
                                <form method="get" id="form-cards-{{$wallet->id}}" action="{{route('owner.wallet.card.list', ['owner_id' => $owner->id, 'wallet_id' => $wallet->id, 'id' => $wallet->id])}}"></form>
                                <form method="get" id="form-edit-{{$wallet->id}}" action="{{route('owner.wallet.edit', ['owner_id' => $owner->id, 'id' => $wallet->id])}}"></form>
                                <form method="post" id="form-delete-{{$wallet->id}}" action="{{route('owner.wallet.destroy', ['owner_id' => $owner->id])}}"> @csrf @method('DELETE') </form>
                                <button type="submit" form="form-cards-{{$wallet->id}}">{{__('Cards')}}</button>
                                @if(!$wallet->main_wallet)
                                    <button type="submit" form="form-edit-{{$wallet->id}}">{{__('Edit')}}</button>
                                    <input type="hidden" form="form-delete-{{$wallet->id}}" name="id" value={{$wallet->id}}>
                                    <button type="submit" form="form-delete-{{$wallet->id}}">{{__('Delete')}}</button>
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
