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
                    <label for="nome">Relevância</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioDispensavel" value="0" @if ($tipoMovimento->relevancia == 0) checked @endif>
                        <label class="form-check-label" for="radioDispensavel">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioDesejavel" value="1" @if ($tipoMovimento->relevancia == 1) checked @endif>
                        <label class="form-check-label" for="radioDesejavel">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioIndispensavel" value="2" @if ($tipoMovimento->relevancia == 2) checked @endif>
                        <label class="form-check-label" for="radioIndispensavel">
                            Indispensável
                        </label>
                    </div>
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" @if ($tipoMovimento->ativo) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='/tipo';">
                </div>
            </form>
            @else
            <form method="post" action="/tipo/novo">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome">
                    <br />
                    <label for="nome">Relevância</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioDispensavel" value="0" checked>
                        <label class="form-check-label" for="radioDispensavel">
                            Dispensável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioDesejavel" value="1">
                        <label class="form-check-label" for="radioDesejavel">
                            Desejável
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="relevancia" id="radioIndispensavel" value="2">
                        <label class="form-check-label" for="radioIndispensavel">
                            Indispensável
                        </label>
                    </div>
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
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
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='/tipo/{{ $tipoMovimento->id }}';">
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