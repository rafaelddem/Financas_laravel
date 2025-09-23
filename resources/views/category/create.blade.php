@php
use App\Enums\Relevance;
@endphp

@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Category')}}</h1>
    </div>
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
                <label for="name">{{__('Name')}}:</label>
                <input type="text" form="form-insert" name="name" id="name" placeholder="{{__('Name')}}" value="{{old('name')}}" required>
                <label for="relevance">{{__('Relevance')}}:</label>
                <select name="relevance" form="form-insert" id="relevance">
                    @foreach (Relevance::cases() as $relevance)
                        <option value='{{ $relevance->value }}' @if(old('relevance') == $relevance->value) selected @endif>{{ __($relevance->name) }}</option>
                    @endforeach
                </select>
                <label>{{__('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="1" checked>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-insert" name="active" value="0">{{__('Inactive')}}</label>
                </div>
                <label for="description">{{__('Description')}}:</label>
                <textarea form="form-insert" class="textarea" name="description" rows="3" placeholder="{{__('Default Placeholder')}}"></textarea>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-insert" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('category.list')}}'">
            </div>
        </div>
        <form method="post" id="form-insert" action="{{route('category.store')}}"> @csrf </form>
    </div>
@endsection
