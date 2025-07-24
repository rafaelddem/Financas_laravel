@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Payment Method")}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-insert" name="active" value=true>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="{{__('Name')}}" required>
                <label for="type">{{__('Type')}}:</label>
                <select form="form-insert" name="type" id="type">
                    <option value="notes">{{__('Notes')}}</option>
                    <option value="transfer">{{__('Transfer')}}</option>
                    <option value="debit">{{__('Debit')}}</option>
                    <option value="credit">{{__('Credit')}}</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('payment-method.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action=" {{route('payment-method.store')}} "> @csrf </form>
    </div>
@endsection
