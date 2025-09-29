@extends('layout')

@section('page_content')
    <div class="presentation">
        <h1>{{__('Transaction')}}</h1>
    </div>
    <div class="presentation">
        <div class="col">
            <input type="submit" form="form-update" value="{{__('Save')}}">
            <input type="button" value="{{__('Return')}}" onclick="window.location='{{app('url')->route('category.list')}}'">
        </div>
        <form method="post" id="form-update" action="{{route('category.update')}}"> @csrf @method('PUT') </form>
    </div>
@endsection
