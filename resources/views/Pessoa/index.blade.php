@extends('layout')

@section('cabecalho')
Pessoa
@endsection

@section('conteudo')
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
                    <button class="btn btn-primary mt-2">Atualizar</button>
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
                    <button class="btn btn-primary mt-2">Adicionar</button>
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
                        <a class="btn btn-primary" href="/pessoa/{{ $pessoa->id }}" role="button">Editar</a>
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