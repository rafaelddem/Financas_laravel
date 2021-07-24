@extends('layout')

@section('cabecalho')
Forma Pagamento
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
                    <button class="btn btn-primary mt-2">Atualizar</button>
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
                    <button class="btn btn-primary mt-2">Adicionar</button>
                </div>
            </form>
            @endif
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($formasPagamento as $formaPagamento)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $formaPagamento->nome }}

                    <span class="d-flex">
                        <a class="btn btn-primary" href="/forma/{{ $formaPagamento->id }}" role="button">Editar</a>
                        <form method="post" action="/forma/{{ $formaPagamento->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($formaPagamento->nome) }}?')">
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