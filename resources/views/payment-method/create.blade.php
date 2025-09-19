@php
use App\Enums\PaymentType;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__("Payment Method")}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <input type="hidden" form="form-insert" name="active" value=true>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="{{__('Name')}}" value="{{old('name')}}" required>
                <label for="type">{{__('Type')}}:</label>
                <select form="form-insert" name="type" id="type">
                    @foreach (PaymentType::cases() as $paymentType)
                        <option value='{{ $paymentType->value }}' @if(old('type') == $paymentType->value) selected @endif>{{ __($paymentType->name) }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('payment-method.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action=" {{route('payment-method.store')}} "> @csrf </form>
    </div>
@endsection
