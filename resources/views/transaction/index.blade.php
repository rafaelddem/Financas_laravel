@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transactions')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="button" value="Novo" onclick="window.location='{{ route('transaction.create') }}'">
            </div>
        </div>
    </div>
@endsection
