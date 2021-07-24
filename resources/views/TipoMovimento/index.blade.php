@extends('layout')

@section('cabecalho')
Tipo Movimento
@endsection

@section('conteudo')
<br />
<div class="container">
    <div class="row">
        <div class="col">
            @if (isset($tipoMovimento))
            <form method="post" action="/tipo/{{ $tipoMovimento->id }}/atualizar">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" value="{{ $tipoMovimento->nome }}">
                    <br />
                    <label for="nome">Indispensavel</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioDispensavel" value="1" @if ($tipoMovimento->indispensavel == 1) checked @endif>
                        <label class="form-check-label" for="radioDispensavel">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioDesejavel" value="2" @if ($tipoMovimento->indispensavel == 2) checked @endif>
                        <label class="form-check-label" for="radioDesejavel">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioIndispensavel" value="3" @if ($tipoMovimento->indispensavel == 3) checked @endif>
                        <label class="form-check-label" for="radioIndispensavel">
                            Indispensável
                        </label>
                    </div>
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" @if ($tipoMovimento->ativo) checked @endif >
                    <br />
                    <button class="btn btn-primary mt-2">Atualizar</button>
                </div>
            </form>
            @else
            <form method="post" action="/tipo/novo">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome">
                    <br />
                    <label for="nome">Indispensavel</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioDispensavel" value="1" checked>
                        <label class="form-check-label" for="radioDispensavel">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioDesejavel" value="2">
                        <label class="form-check-label" for="radioDesejavel">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="indispensavel" id="radioIndispensavel" value="3">
                        <label class="form-check-label" for="radioIndispensavel">
                            Indispensável
                        </label>
                    </div>
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" checked>
                    <br />
                    <button class="btn btn-primary mt-2">Adicionar</button>
                </div>
            </form>
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($tiposMovimento as $tipoMovimento)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $tipoMovimento->nome }}

                    <span class="d-flex">
                        <a class="btn btn-primary" href="/tipo/{{ $tipoMovimento->id }}" role="button">Editar</a>
                        <form method="post" action="/tipo/{{ $tipoMovimento->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($tipoMovimento->nome) }}?')">
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