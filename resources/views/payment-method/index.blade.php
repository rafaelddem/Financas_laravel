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
                            <span class="td-content">
                                {{ $paymentMethod->name }}
                                @switch($paymentMethod->type)
                                    @case('notes') <span class="tag">({{__('Notes')}})</span> @break
                                    @case('transfer') <span class="tag">({{__('Transfer')}})</span> @break
                                    @case('debit') <span class="tag">({{__('Debit')}})</span> @break
                                    @case('credit') <span class="tag">({{__('Credit')}})</span> @break
                                @endswitch
                                @if(!$paymentMethod->active)
                                    <span class="tag">{{__('Inactive')}}</span>
                                @endif
                            </span>
                            <form method="post" id="form-update{{$paymentMethod->id}}" action="{{route('payment-method.update')}}"> @csrf @method('PUT') </form>
                            <form method="post" id="form-delete{{$paymentMethod->id}}" action="{{route('payment-method.destroy')}}"> @csrf @method('DELETE') </form>
                            <div class="td-buttons">
                                <input type="hidden" form="form-update{{$paymentMethod->id}}" name="id" value={{$paymentMethod->id}}>
                                <input type="hidden" form="form-update{{$paymentMethod->id}}" name="active" value={{!$paymentMethod->active}}>
                                @if ($paymentMethod->active)
                                    <button type="submit" form="form-update{{$paymentMethod->id}}">{{__('Inactivate')}}</button>
                                @else
                                    <button type="submit" form="form-update{{$paymentMethod->id}}">{{__('Activate')}}</button>
                                @endif
                                <input type="hidden" form="form-delete{{$paymentMethod->id}}" name="id" value={{$paymentMethod->id}}>
                                <button type="submit" form="form-delete{{$paymentMethod->id}}">{{__('Delete')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
