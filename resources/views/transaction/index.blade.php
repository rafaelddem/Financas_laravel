@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transactions')}}</h1>
    </div>
    <div class="presentation">
        <div class="col">
            <input type="button" value="Novo" onclick="window.location='{{ route('transaction.create') }}'">
        </div>
        <div class="col">
            <table>
                @foreach($transactions as $transaction)
                <tr>
                    <td class="td-item">
                        <span class="td-content">
                            {{ $transaction->transaction_date->format('d/m/Y') }} | {{ $transaction->net_value_formatted }} | {{ $transaction->title }}
                            @if($transaction->sourceWallet->owner_id == $transaction->destinationWallet->owner_id)
                                <span class="tag">{{ $transaction->sourceWallet->name }} <i class="fa-solid fa-left-right"></i> {{ $transaction->destinationWallet->name }}</span>
                            @elseif($transaction->sourceWallet->owner_id == env('MY_OWNER_ID'))
                                <span class="tag"><i class="fa-solid fa-caret-down"></i> {{ $transaction->sourceWallet->name }}</span>
                            @elseif($transaction->destinationWallet->owner_id == env('MY_OWNER_ID'))
                                <span class="tag"><i class="fa-solid fa-caret-up"></i> {{ $transaction->destinationWallet->name }}</span>
                            @endif
                            <span class="tag">{{ __($transaction->relevance->name)}}</span>
                        </span>
                        <form method="get" id="form-update-{{$transaction->id}}" action="{{route('category.edit', ['id' => $transaction->id])}}"></form>
                        <form method="post" id="form-delete-{{$transaction->id}}" action="{{route('category.destroy')}}"> @csrf @method('DELETE') </form>
                        <div class="td-buttons">
                            <button type="submit" form="form-update-{{$transaction->id}}">{{__('Edit')}}</button>
                            <input type="hidden" form="form-delete-{{$transaction->id}}" name="id" value={{$transaction->id}}>
                            <button type="submit" form="form-delete-{{$transaction->id}}">{{__('Delete')}}</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
