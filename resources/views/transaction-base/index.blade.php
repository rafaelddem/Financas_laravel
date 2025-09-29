@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Bases')}}</h1>
    </div>
    <div class="presentation">
        <div class="col">
            <input type="button" value="Novo" onclick="window.location='{{ route('transaction-base.create') }}'">
        </div>
        <div class="col">
            <table>
                @foreach($transactionBases as $transactionBase)
                <tr>
                    <td class="td-item">
                        <span class="td-content">{{$transactionBase}}</span>
                        <form method="post" id="form-delete-{{$transactionBase->id}}" action="{{route('transaction-base.destroy')}}"> @csrf @method('DELETE') </form>
                        <div class="td-buttons">
                            <input type="hidden" form="form-delete-{{$transactionBase->id}}" name="id" value={{$transactionBase->id}}>
                            <button type="submit" form="form-delete-{{$transactionBase->id}}">{{__('Delete')}}</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
