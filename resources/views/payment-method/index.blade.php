@php
use App\Enums\PaymentType;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Payment Method")}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('payment-method.create') }}'">
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <table>
                    @foreach($paymentMethods as $paymentMethod)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $paymentMethod->name }}
                                <span class="tag">{{ __($paymentMethod->type->name) }}</span>
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
