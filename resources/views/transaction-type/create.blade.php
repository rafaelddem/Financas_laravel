@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="{{__('Name')}}" required>
                <label for="relevance">{{__('Relevance')}}:</label>
                <select name="relevance" form="form-insert" id="relevance">
                    <option value='banal'>{{__('Banal')}}</option>
                    <option value='relevant'>{{__('Relevant')}}</option>
                    <option value='indispensable'>{{__('Indispensable')}}</option>
                </select>
                <label>{{__('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="1" checked>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="0">{{__('Inactive')}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action="{{route('transaction-type.store')}}"> @csrf </form>
    </div>
@endsection
