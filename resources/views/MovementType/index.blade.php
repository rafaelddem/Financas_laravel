@extends('layout')

@section('header')
Tipo Movimento
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
            @if (isset($movementType))
            <form method="post" action="{{route('updateMovementType', $movementType->id)}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $movementType->name }}">
                    <br />
                    <label for="relevance">Relevância</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioUnnecessary" value="0" @if ($movementType->relevance == 0) checked @endif>
                        <label class="form-check-label" for="radioUnnecessary">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioDesirable" value="1" @if ($movementType->relevance == 1) checked @endif>
                        <label class="form-check-label" for="radioDesirable">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioNecessary" value="2" @if ($movementType->relevance == 2) checked @endif>
                        <label class="form-check-label" for="radioNecessary">
                            Indispensável
                        </label>
                    </div>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" @if ($movementType->active) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{route('listMovementType')}}';">
                </div>
            </form>
            @else
            <form method="post" action="{{route('createMovementType')}}">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="relevance">Relevância</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioUnnecessary" value="0" checked>
                        <label class="form-check-label" for="radioUnnecessary">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioDesirable" value="1">
                        <label class="form-check-label" for="radioDesirable">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevance" id="radioNecessary" value="2">
                        <label class="form-check-label" for="radioNecessary">
                            Indispensável
                        </label>
                    </div>
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
                @foreach($movementTypes as $movementType)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $movementType->name }}
                    @if(!$movementType->active)
                        (inativo)
                    @endif

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='{{route('findMovementType', $movementType->id)}}';">
                        <form method="post" action="{{route('deleteMovementType', $movementType->id)}}"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($movementType->name) }}?')">
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