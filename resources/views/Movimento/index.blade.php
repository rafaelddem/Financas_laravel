@extends('layout')

@section('cabecalho')
Movimento
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
            @if (isset($movimento))
            <form method="post" action="/movimento/{{ $movimento->id }}/atualizar">
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
            <form method="post" action="/movimento/novo">
                @csrf
                <div class="container">
                    <label for="nome">Parcelas</label>
                    <input type="number" class="form-control" name="parcelas" id="parcelas" value="1">
                    <br />
                    <label for="nome">Data Movimento</label>
                    <input type="date" class="form-control" name="dataMovimento" id="dataMovimento">
                    <br />
                    <label for="nome">Tipo de Movimento:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="tipoMovimento" id="tipoMovimento">
                        <option value=0>Selecione...</option>
                        @foreach($tipoMovimentos as $tipoMovimento)
                            <option value="{{ $tipoMovimento->id }}">{{ $tipoMovimento->nome }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="nome">Valor Inicial</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" class="form-control" id="valorInicial">
                    </div>
                    <br />
                    <label for="nome">Valor Desconto</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" class="form-control" id="valorDesconto">
                    </div>
                    <br />
                    <label for="nome">Valor Arredondamento</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" class="form-control" id="valorArredondamento">
                    </div>
                    <br />
                    <label for="nome">Valor Final</label>
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <div class="input-group-text">R$</div>
                        </div>
                        <input type="text" class="form-control" id="valorFinal">
                    </div>
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
                    <label for="nome">Descrição</label>
                    <textarea class="form-control" aria-label="With textarea" name="descricao" id="descricao"></textarea>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Salvar">
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