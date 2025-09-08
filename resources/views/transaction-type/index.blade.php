@php
use App\Enums\Relevance;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('transaction-type.create') }}'">
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <table>
                    @foreach($transactionTypes as $transactionType)
                    <tr>
                        <td class="td-item">
                            <span class="td-content">
                                {{ $transactionType->name }}
                                <span class="tag">{{ __($transactionType->relevance->name)}}</span>
                                @if(!$transactionType->active)
                                    <span class="tag">{{__('Inactive')}}</span>
                                @endif
                            </span>
                            <form method="get" id="form-update-{{$transactionType->id}}" action="{{route('transaction-type.edit', ['id' => $transactionType->id])}}"></form>
                            <form method="post" id="form-delete-{{$transactionType->id}}" action="{{route('transaction-type.destroy')}}"> @csrf @method('DELETE') </form>
                            <div class="td-buttons">
                                <button type="submit" form="form-update-{{$transactionType->id}}">{{__('Edit')}}</button>
                                <input type="hidden" form="form-delete-{{$transactionType->id}}" name="id" value={{$transactionType->id}}>
                                <button type="submit" form="form-delete-{{$transactionType->id}}">{{__('Delete')}}</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
