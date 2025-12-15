@extends('index')

@section('page_content')
    <div class="centered centered_form">
        <div class="flex-container">
            <div class="col">
                <h2 class="card-title">{{__('Fill out the form')}}</h2>
            </div>
            <div class="col">
                <label for="username">{{__('Username')}}:</label>
                <input type="text" form="form-insert" name="username" placeholder="{{__('Username')}}" required>
                <label for="password">{{__('Password')}}:</label>
                <input type="password" form="form-insert" name="password" placeholder="{{__('Password')}}" required>
            </div>
            <div class="col">
                <button type="submit" form="form-insert">{{__('Sign in')}}</button>
                <form method="post" id="form-insert" action="{{route('sign_in')}}"> @csrf </form>
            </div>
        </div>
    </div>
@endsection