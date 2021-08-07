@extends('layout')

@section('cabecalho')
Carteira
@endsection

@section('conteudo')
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
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
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='/carteira';">
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
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
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
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='/carteira/{{ $carteira->id }}';">
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