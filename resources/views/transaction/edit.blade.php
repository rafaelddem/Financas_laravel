@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <input type="submit" form="form-update" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
            </div>
        </div>
        <form method="post" id="form-update" action="{{route('transaction-type.update')}}"> @csrf @method('PUT') </form>
    </div>
@endsection
