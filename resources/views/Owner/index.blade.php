@extends('layout')

@section('header')
Pessoa
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
            @if (isset($owner))
            <form method="post" action="{{route('updateOwner', $owner->id)}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $owner->name }}">
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" @if ($owner->active) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{route('listOwner')}}';">
                </div>
            </form>
            @else
            <form method="post" action="{{route('createOwner')}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
                </div>
            </form>
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($owners as $owner)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $owner->name }} 
                    @if(!$owner->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='{{route('findOwner', $owner->id)}}';">
                        <form method="post" action="{{route('deleteOwner', $owner->id)}}"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($owner->name) }}?')">
                            @csrf
                            <input class="btn btn-primary" type="submit" value="Excluir" style="background-color: red;border-color: red;">
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection