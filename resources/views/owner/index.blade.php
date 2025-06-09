@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Owner')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col_1_2">
                <h2 class="card-title">Preencha o formulário</h2>
                <form method="post" action="{{route('owner.store')}}">
                    @csrf
                    <label for="nome">Nome:</label>
                    <input type="text" name="name" placeholder="Nome" required>
                    <label for="nome">Status:</label>
                    <div class="radio-container">
                        <label class="radio-option"><input type="radio" name="active" value="1" checked>Ativo</label>
                        <label class="radio-option"><input type="radio" name="active" value="0">Inativo</label>
                    </div>
                    <button type="submit">Salvar</button>
                </form>
            </div>
            <div class="col_1_2">
                <h2 class="card-title">Lista de Valores</h2>
                <table>
                    @foreach($owners as $owner)
                    <tr>
                        <td>
                            <span>{{ $owner->name }} @if(!$owner->active) (inativo) @endif</span>
                            <div class="td-buttons">
                                <form method="post" action="{{route('owner.update')}}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="id" value={{$owner->id}}>
                                    <input type="hidden" name="active" value={{!$owner->active}}>
                                    <button type="submit">@if ($owner->active) Inativar @else Ativar @endif</button>
                                </form>
                                <form method="get" action="{{route('owner.wallet.list', ['owner_id' => $owner->id])}}">
                                    <button type="submit">{{__('Wallets')}}</button>
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
