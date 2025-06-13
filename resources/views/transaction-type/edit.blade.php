@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction Type')}}</h1>
    </div>
    <div class="presentation">
        <div class="row">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-update" name="id" value={{$transactionType->id}}>
                <label>{{__('Name')}}:</label>
                <div class="row">
                    <label><strong>{{$transactionType->name}}</strong></label>
                </div>
                <label for="relevance">{{__('Relevance')}}:</label>
                <select form="form-update" name="relevance" id="relevance">
                    <option value='banal' @if ($transactionType->relevance == 'banal') selected @endif>{{__('Banal')}}</option>
                    <option value='relevant' @if ($transactionType->relevance == 'relevant') selected @endif>{{__('Relevant')}}</option>
                    <option value='indispensable' @if ($transactionType->relevance == 'indispensable') selected @endif>{{__('Indispensable')}}</option>
                </select>
                <label>{{_('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="1" @if ($transactionType->active) checked @endif>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="0" @if (!$transactionType->active) checked @endif>{{__('Inactive')}}</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <input type="submit" form="form-update" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
            </div>
        </div>
        <form method="post" id="form-update" action="{{route('transaction-type.update')}}"> @csrf @method('PUT') </form>
    </div>
@endsection
