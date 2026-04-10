@extends('layout')

@section('page_content')
    <div class="presentation">
        <div class="flex-container">
            <div class="col">
                <h1>{{__('Configurations')}}</h1>
            </div>
            @foreach($configurations as $configuration)
                <div class="col_50 col_sm">
                    <h4><label id="installmentData" class="label-as-input" title="{{ $configuration->description }}">{{ $configuration->name }}:</label></h4>
                </div>
                <div class="col_50 sm_col">
                    <label for="value">{{ $configuration->key }}</label>
                    <input type="text" form="form-config" name="{{ $configuration->key }}" value="{{ $configuration->value }}" required>
                </div>
            @endforeach
            <div class="col">
                <div class="flex-container">
                    <div class="col_child">
                        <button type="submit" form="form-config">{{__('Save')}}</button>
                    </div>
                </div>
            </div>
            <form method="post" id="form-config" action="{{route('configuration.update')}}"> @csrf @method('PUT') </form>
        </div>
    </div>
@endsection
