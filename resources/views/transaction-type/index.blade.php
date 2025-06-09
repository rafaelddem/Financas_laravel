@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('transaction-type.create') }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    @foreach($transactionTypes as $transactionType)
                    <tr>
                        <td class="td-item">
                            <div class="td-content">
                                <span class="item-value">
                                    {{ $transactionType->name }}
                                    @switch($transactionType->relevance)
                                        @case('banal') <span class="tag">{{__('Banal')}}</span> @break
                                        @case('relevant') <span class="tag">{{__('Relevant')}}</span> @break
                                        @case('indispensable') <span class="tag">{{__('Indispensable')}}</span> @break
                                    @endswitch
                                    @if(!$transactionType->active)
                                        <span class="tag">inativo</span>
                                    @endif
                                </span>
                            </div>
                            <div class="td-buttons">
                                <form method="get" action="{{route('transaction-type.edit', ['id' => $transactionType->id])}}">
                                    <button type="submit">Editar</button>
                                </form>
                                <form method="post" action="{{route('transaction-type.destroy')}}">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="id" value={{$transactionType->id}}>
                                    <button type="submit">Remover</button>
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
