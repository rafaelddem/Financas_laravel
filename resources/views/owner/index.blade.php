@extends('layout')

@section('header')
{{__('Owner')}}
@endsection

@section('content')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} <br />
        @endforeach
    </div>
@endif
@if(!empty($message))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<br />
<div class="container">
    <div class="row">
        <div class="col">
            <form method="post" action="{{route('owner.store')}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name">
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" value=1 checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Salvar">
                </div>
            </form>
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($owners as $itemOwner)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $itemOwner->name }}
                    @if(!$itemOwner->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <form method="post" action="{{route('owner.update')}}">
                            @csrf @method('PUT')
                            <input type="hidden" name="id" value={{$itemOwner->id}}>
                            <input type="hidden" name="active" value={{!$itemOwner->active}}>
                            <button type="submit" class="btn btn-primary">@if ($itemOwner->active) Inativar @else Ativar @endif</button>
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection
