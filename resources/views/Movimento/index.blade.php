@extends('layout')

@section('cabecalho')
Movimento
@endsection

@section('conteudo')
<script>

    function criaParcela() 
    {
        var numeroParcelas = parseFloat(document.getElementById("numeroParcelas").value);
        var code = "";
        for (let parcela = 1; parcela <= numeroParcelas; parcela++) {
            code += "<div class='input-group'>";
            code +=     "<div class='input-group-text col-md-3'>Parcela " + parcela + "</div>";
            code +=         "<div class='form-group col-md-4'>";
            code +=             "<input type='text' class='form-control' name='valorInicialParcela[]' id='valorInicialParcela" + parcela + "' required>";
            code +=         "</div>";
            code +=     "<div class='form-group col-md-1'></div>";
            code +=         "<div class='form-group col-md-4'>";
            code +=             "<input type='date' class='form-control' name='dataVencimentoParcela[]' id='dataVencimentoParcela" + parcela + "' required>";
            code +=         "</div>";
            code +=     "</div>";
            code += "<br />";
        }
        document.getElementById("parcelas").innerHTML = code;

        calcularValorFinal();
    }

    function calcularValorFinal() 
    {
        var valorInicial = parseFloat(document.getElementById("valorInicial").value);
        var valorDesconto = parseFloat(document.getElementById("valorDesconto").value);
        var valorArredondamento = parseFloat(document.getElementById("valorArredondamento").value);

        var valorFinal = valorInicial - valorDesconto + valorArredondamento;

        if (valorFinal < 0) {
            alert('O valor final (valor inicial subtraído o desconto e o arredondamento) não pode ser negativo');
        }

        document.getElementById("valorFinal").value = valorFinal;

        parcelar();
    }

    function calcularValorArredondamento() 
    {
        var valorInicial = parseFloat(document.getElementById("valorInicial").value);
        var valorDesconto = parseFloat(document.getElementById("valorDesconto").value);
        var valorFinal = parseFloat(document.getElementById("valorFinal").value);
        var valorArredondamento = valorFinal - valorInicial + valorDesconto;

        document.getElementById("valorArredondamento").value = valorArredondamento;

        parcelar();
    }

    function parcelar() 
    {
        var numeroParcelas = parseFloat(document.getElementById("numeroParcelas").value);
        var valorFinal = parseFloat(document.getElementById("valorFinal").value);

        if (valorFinal <= 0) {
            return
        }

        if (numeroParcelas < 1) {
            alert('O número de parcelas deve ser maior que 0');
            return
        }

        valorFinalCentavos = valorFinal * 100;
        valorParcela = Math.floor(valorFinalCentavos / numeroParcelas) / 100;
        diferenca = (valorFinalCentavos % numeroParcelas) / 100;

        document.getElementById("valorInicialParcela1").value = valorParcela + diferenca;
        for (let parcelas = 2; parcelas <= numeroParcelas; parcelas++) {
            document.getElementById("valorInicialParcela" + parcelas).value = valorParcela;
        }
    }

    // function formatarValor(valor) {
    //     var formatter = new Intl.NumberFormat('pt-BR', {
    //         style: 'currency', 
    //         currency: 'BRL', 
    //         minimumFractionDigits: 2, 
    //     });

    //     return formatter.format(valor);
    // }

</script>
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
            <form method="post" action="/movimento/novo">
                @csrf
                <div class="container">
                    <label for="nome">Data Movimento</label>
                    <input type="date" class="form-control" name="dataMovimento" id="dataMovimento" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" required>
                    <br />
                    <label for="nome">Origem:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="carteiraOrigem" id="carteiraOrigem">
                        <optgroup label="Genéricas">
                        @foreach($carteirasSistema as $carteira)
                            <option value="{{ $carteira->id }}">{{ $carteira->nome }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($carteirasPessoais as $carteira)
                            <option value="{{ $carteira->id }}">{{ $carteira->nome }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($carteirasTerceiros as $key => $carteira)
                            <option value="{{ $key }}">{{ $carteira }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />
                    <label for="nome">Destino:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="carteiraDestino" id="carteiraDestino">
                        <optgroup label="Genéricas">
                        @foreach($carteirasSistema as $carteira)
                            <option value="{{ $carteira->id }}">{{ $carteira->nome }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($carteirasPessoais as $carteira)
                            <option value="{{ $carteira->id }}">{{ $carteira->nome }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($carteirasTerceiros as $key => $carteira)
                            <option value="{{ $key }}">{{ $carteira }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />
                    <label for="nome">Tipo de Movimento:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="tipoMovimento" id="tipoMovimento">
                        <option value="0">Selecione...</option>
                        @foreach($tipoMovimentos as $tipoMovimento)
                            <option value="{{ $tipoMovimento->id }}">{{ $tipoMovimento->nome }}</option>
                        @endforeach
                    </select>
                    <br />

                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="nome">Valor Inicial</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="valorInicial" id="valorInicial" value="0" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="nome">Valor Desconto</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="valorDesconto" id="valorDesconto" value="0" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="nome">Valor Arredondamento</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="valorArredondamento" id="valorArredondamento" value="0" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="nome">Valor Final</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="valorFinal" id="valorFinal" value="0" step="any" onchange="calcularValorArredondamento()">
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="card">
                        <div class="card-header">Parcelas</div>
                        <div class="card-body">
                            <label for="nome">Número de Parcelas</label>
                                <input type="number" class="form-control" name="numeroParcelas" id="numeroParcelas" value="1" onChange="criaParcela()" required>
                            <br />
                            <!-- @include('Movimento.parcela', ['parcelas' => Request::input('numeroParcelas')]) -->
                            <div name="parcelas" id="parcelas">
                            </div>
                        </div>
                    </div>
                    <br />
                    <label for="nome">Relevância</label>
                    <div class="input-group">
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevancia" id="radioDispensavel" value="0" checked>
                            <label class="form-check-label" for="radioDispensavel">
                                Dispensável
                            </label>
                        </div>
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevancia" id="radioDesejavel" value="1">
                            <label class="form-check-label" for="radioDesejavel">
                                Desejável
                            </label>
                        </div>
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevancia" id="radioIndispensavel" value="2">
                            <label class="form-check-label" for="radioIndispensavel">
                                Indispensável
                            </label>
                        </div>
                    </div>
                    <br />
                    <label for="nome">Descrição</label>
                    <textarea class="form-control" aria-label="With textarea" name="descricao" id="descricao"></textarea>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Salvar">
                </div>
            </form>
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($movimentos as $movimento)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $movimento }}

                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='/forma/{{ $movimento->id }}';">
                        <form method="post" action="/forma/{{ $movimento->id }}/excluir"
                            onsubmit="return confirm('Tem certeza que deseja remover este movimento?')">
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

<script> criaParcela(); </script>
@endsection