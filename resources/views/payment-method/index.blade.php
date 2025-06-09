@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Payment Method")}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('payment-method.create') }}'">
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table>
                    @foreach($paymentMethods as $paymentMethod)
                    <tr>
                        <td class="td-item">
                            <div class="td-content">
                                <span class="item-value">
                                    {{ $paymentMethod->name }}
                                    @switch($paymentMethod->type)
                                        @case('notes') <span class="tag">({{__('Notes')}})</span> @break
                                        @case('transfer') <span class="tag">({{__('Transfer')}})</span> @break
                                        @case('debit') <span class="tag">({{__('Debit')}})</span> @break
                                        @case('credit') <span class="tag">({{__('Credit')}})</span> @break
                                    @endswitch
                                    @if(!$paymentMethod->active)
                                        <span class="tag">inativo</span>
                                    @endif
                                </span>
                            </div>
                            <div class="td-buttons">
                                <form method="post" action="{{route('payment-method.update')}}">
                                    @csrf @method('PUT')
                                    <input type="hidden" name="id" value={{$paymentMethod->id}}>
                                    <input type="hidden" name="active" value={{!$paymentMethod->active}}>
                                    <button type="submit">@if ($paymentMethod->active) Inativar @else Ativar @endif</button>
                                </form>
                                <form method="post" action="{{route('payment-method.destroy')}}">
                                    @csrf @method('DELETE')
                                    <input type="hidden" name="id" value={{$paymentMethod->id}}>
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
