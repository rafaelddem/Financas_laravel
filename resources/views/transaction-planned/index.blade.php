@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Planned Entry')}}</h1>
    </div>
    <div class="presentation">
        <div class="col">
            <input type="button" value="{{__('Create')}}" onclick="window.location='{{ route('transaction-planned.create') }}'">
        </div>
        <div class="col">
            <table>
                @foreach($transactions as $transaction)
                <tr>
                    <td class="td-item">
                        <span class="td-content">
                            {{ $transaction->transaction_date->format('d/m/Y') }} | {{ $transaction->net_value_formatted }} | {{ $transaction->title }}
                            @if($transaction->total_planned > 1)
                                <span class="tag"><i class="fa-solid"></i>+ {{$transaction->total_planned - 1}}</span>
                            @endif
                            <span class="tag">{{ __($transaction->relevance->name)}}</span>
                        </span>
                        <div class="td-buttons">
                            <button type="button" onclick="window.location='{{ route('transaction-planned.show', ['id' => $transaction->transaction_planned_id]) }}'" title="{{__('Transaction Details')}}">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
