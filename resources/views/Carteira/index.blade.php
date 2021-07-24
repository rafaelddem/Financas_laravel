@extends('layout')

@section('cabecalho')
Carteira
@endsection

@section('conteudo')
<br />
<div class="container">
    <div class="row">
        <div class="col">
            @if (isset($carteira))
            <form method="post" action="/carteira/{{ $carteira->id }}/atualizar">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome" value="{{ $carteira->nome }}">
                    <br />
                    <label for="nome">Pertencente a:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="pessoa" id="pessoa">
                        @foreach($pessoas as $pessoa)
                            <option value="{{ $pessoa->id }}" @if ($carteira->pessoa == $pessoa->id) selected @endif>{{ $pessoa->nome }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="ativo" id="ativo" @if ($carteira->ativo) checked @endif >
                    <br />
                    <button class="btn btn-primary mt-2">Atualizar</button>
                </div>
            </form>
            @else
            <form method="post" action="/carteira/novo">
                @csrf
                <div class="container">
                    <label for="nome">Nome</label>
                    <input type="text" class="form-control" name="nome" id="nome">
                    <br />
                    <label for="nome">Pertencente a:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="pessoa" id="pessoa">
                        @foreach($pessoas as $pessoa)
                            <option value="{{ $pessoa->id }}">{{ $pessoa->nome }}</option>
                        @endforeach
                    </select>
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
                @foreach($carteiras as $carteira)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $carteira->nome }} - {{ $carteira->dono }}

                    <span class="d-flex">
                        <a class="btn btn-primary" href="/carteira/{{ $carteira->id }}" role="button">Editar</a>
                        <form method="post" action="/carteira/{{ $carteira->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover {{ addslashes($carteira->nome) }}?')">
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