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
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-update" name="id" value={{$transactionType->id}}>
                <label>{{__('Name')}}:</label>
                <div class="flex-container">
                    <label><strong>{{$transactionType->name}}</strong></label>
                </div>
                <label for="relevance">{{__('Relevance')}}:</label>
                <select form="form-update" name="relevance" id="relevance">
                    @foreach (Relevance::values() as $value => $presentation)
                        <option value='{{ $value }}' @if ($transactionType->relevance == $value) selected @endif>{{ $presentation }}</option>
                    @endforeach
                </select>
                <label>{{_('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="1" @if ($transactionType->active) checked @endif>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="0" @if (!$transactionType->active) checked @endif>{{__('Inactive')}}</label>
                </div>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-update" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('transaction-type.list')}}'">
            </div>
        </div>
        <form method="post" id="form-update" action="{{route('transaction-type.update')}}"> @csrf @method('PUT') </form>
    </div>
@endsection
