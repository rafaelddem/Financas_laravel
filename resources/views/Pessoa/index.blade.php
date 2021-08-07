@extends('layout')

@section('cabecalho')
Pessoa
@endsection

@section('conteudo')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }} <br />
        @endforeach
    </div>
@endif
@if(!empty($mensagem))
<div class="alert alert-success">
    {{ $mensagem }}
</div>
@endif

<br />
<div class="container">
    <div class="row">
        <div class="col">
            @if (isset($pessoa))
            <form method="post" action="/pessoa/{{ $pessoa->id }}/atualizar">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" value="{{ $pessoa->nome }}">
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" @if ($pessoa->ativo) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='/pessoa';">
                </div>
            </form>
            @else
            <form method="post" action="/pessoa/novo">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome">
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
                @foreach($pessoas as $pessoa)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $pessoa->nome }}

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='/pessoa/{{ $pessoa->id }}';">
                        <form method="post" action="/pessoa/{{ $pessoa->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($pessoa->nome) }}?')">
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