@extends('layout')

@section('cabecalho')
Movimento
@endsection

@section('conteudo')
<br />
<div class="container">
    <div class="row">
        <div class="col">
            @if (isset($formaPagamento))
            <form method="post" action="/forma/{{ $formaPagamento->id }}/atualizar">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" value="{{ $formaPagamento->nome }}">
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" @if ($formaPagamento->ativo) checked @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='/forma';">
                </div>
            </form>
            @else
            <form method="post" action="/forma/novo">
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
                @foreach($movimentos as $movimento)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    R$ {{ $movimento->valorFinal }} - {{ $movimento->descricao }}

                    <span class="d-flex">
                        <input class="btn btn-primary mt-2" type="button" value="Editar" onclick="window.location='/forma/{{ $movimento->id }}';">
                        <form method="post" action="/forma/{{ $movimento->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover este movimento?')">
                            @csrf
                            <input class="btn btn-primary" type="submit" value="Excluir">
                        </form>
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
@endsection