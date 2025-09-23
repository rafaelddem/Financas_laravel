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
                <input type="hidden" form="form-update" name="id" value={{$category->id}}>
                <label>{{__('Name')}}:</label>
                <div class="flex-container">
                    <label><strong>{{$category->name}}</strong></label>
                </div>
                <label for="relevance">{{__('Relevance')}}:</label>
                <select name="relevance" form="form-update" id="relevance">
                    @foreach (Relevance::cases() as $relevance)
                        <option value='{{ $relevance->value }}' @if ($category->relevance->value == $relevance->value) selected @endif>{{ __($relevance->name) }}</option>
                    @endforeach
                </select>
                <label>{{_('Status')}}:</label>
                <div class="radio-container">
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="1" @if ($category->active) checked @endif>{{__('Active')}}</label>
                    <label class="radio-option"><input type="radio" form="form-update" name="active" value="0" @if (!$category->active) checked @endif>{{__('Inactive')}}</label>
                </div>
                <label for="description">{{__('Description')}}:</label>
                <textarea form="form-update" class="textarea" name="description" rows="3" placeholder="{{__('Default Placeholder')}}">{{$category->description}}</textarea>
            </div>
        </div>
        <div class="flex-container">
            <div class="col">
                <input type="submit" form="form-update" value="{{__('Save')}}">
                <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('category.list')}}'">
            </div>
        </div>
        <form method="post" id="form-update" action="{{route('category.update')}}"> @csrf @method('PUT') </form>
    </div>
@endsection
