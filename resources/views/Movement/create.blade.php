@extends('layout')

@section('header')
Movimento
@endsection

@section('content')
<script>

    function fillTitle(item)
    {
        var movementType = item.options[item.selectedIndex].text;
        document.getElementById("title").value = movementType;
    }

    function calculateNetValue(rounding) 
    {
        var grossValue = prepareValue(document.getElementById("gross_value").value);
        var descountValue = prepareValue(document.getElementById("descount_value").value);
        var roundingValue = prepareValue(document.getElementById("rounding_value").value);
        var netValue = prepareValue(document.getElementById("net_value").value);

        if (rounding) {
            var roundingValue = netValue - grossValue + descountValue;
        } else {
            var netValue = grossValue - descountValue + roundingValue;
        }

        document.getElementById("gross_value").value = formatValue(grossValue);
        document.getElementById("descount_value").value = formatValue(descountValue);
        document.getElementById("rounding_value").value = formatValue(roundingValue);
        document.getElementById("net_value").value = formatValue(netValue);

        calculateInstallment();
    }

    function createInstallment() 
    {
        var installments = parseFloat(document.getElementById("installments").value);
        var code = "";
        for (let installment = 1; installment <= installments; installment++) {
            code += "<div class='input-group'>";
            code +=     "<div class='input-group-text col-md-3'>Parcela " + installment + "</div>";
            code +=         "<div class='form-group col-md-4'>";
            code +=             "<input type='text' class='form-control' name='installment_gross_value[]' id='installment_gross_value" + installment + "' value='R$ 0,00' required>";
            code +=         "</div>";
            code +=     "<div class='form-group col-md-1'></div>";
            code +=         "<div class='form-group col-md-4'>";
            code +=             "<input type='date' class='form-control' name='installment_duo_date[]' id='installment_duo_date" + installment + "' value='{{ Carbon\Carbon::now()->format('Y-m-d') }}' required>";
            code +=         "</div>";
            code +=     "</div>";
            code += "<br />";
        }
        document.getElementById("installmentsCode").innerHTML = code;

        calculateInstallment();
    }

    function calculateInstallment() 
    {
        var installments = parseFloat(document.getElementById("installments").value);
        var netValue = prepareValue(document.getElementById("net_value").value);

        if (netValue <= 0) {
            return
        }

        if (installments < 1) {
            alert('O número de parcelas deve ser maior que 0');
            return
        }

        valueInCents = netValue * 100;
        installmentValue = Math.floor(valueInCents / installments) / 100;
        diference = (valueInCents % installments) / 100;

        document.getElementById("installment_gross_value1").value = formatValue(installmentValue + diference);
        for (let installment = 2; installment <= installments; installment++) {
            document.getElementById("installment_gross_value" + installment).value = formatValue(installmentValue);
        }
    }

    function prepareValue(value) {
        var negative = value.charAt(0) == '-';
        var regexCode = /[^0-9]/gi;

		cleanValue = value.replace(regexCode, "");
        netValue = cleanValue / 100;

        if (negative) {
            netValue = netValue * -1;
        }

        return netValue;
    }

    function formatValue(valor) {
        var formatter = new Intl.NumberFormat('pt-BR', {
            style: 'currency', 
            currency: 'BRL', 
            minimumFractionDigits: 2, 
        });

        return formatter.format(valor);
    }

</script>
@if ($errors->any())
    <div class="alert alert-danger">
        @foreach ($errors->all() as $error)
            {{ $error }}<br />
        @endforeach
    </div>
@endif
@if(!empty($message))
<div class="alert alert-success">
    {{ $message }}
</div>
@endif

<br />
<div class="container">
    <div class="row">
        <div class="col">
            <form method="post" action="{{route('storeMovement')}}">
                @csrf
                <div class="container">
                    <label for="movement_type">Tipo de Movimento:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="movement_type" id="movement_type" onchange="fillTitle(this)">
                        <option value="0">Selecione...</option>
                        @foreach($movementTypes as $movementType)
                            <option value="{{ $movementType->id }}" @if ($movementType->id == $movement->movement_type) selected @endif>{{ $movementType->name }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="title">Título</label>
                    <input type="text" class="form-control" name="title" id="title" value="@if(isset($movement)){{$movement->title}}@endif" required>
                    <br />
                    <label for="movement_date">Data Movimento</label>
                    <input type="date" class="form-control" name="movement_date" id="movement_date" value="@if(isset($movement)){{Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $movement->movement_date)->format('Y-m-d')}}@else{{Carbon\Carbon::now()->format('Y-m-d')}}@endif" required>
                    <br />
                    <label for="source_wallet">Origem:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="source_wallet" id="source_wallet">
                        <optgroup label="Genéricas">
                        @foreach($systemWallets as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->source_wallet) selected @endif>{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($personalWallets as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->source_wallet) selected @endif>{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($thirdPartyWallet as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->source_wallet) selected @endif>{{ $wallet->ownerName() }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />
                    <label for="destination_wallet">Destino:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="destination_wallet" id="destination_wallet">
                        <optgroup label="Genéricas">
                        @foreach($systemWallets as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->destination_wallet) selected @endif>{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($personalWallets as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->destination_wallet) selected @endif>{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($thirdPartyWallet as $wallet)
                            <option value="{{ $wallet->id }}" @if ($wallet->id == $movement->destination_wallet) selected @endif>{{ $wallet->ownerName() }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />

                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="gross_value">Valor Inicial</label>
                            <div class="input-group col-mb-2">
                                <input type="text" class="form-control" name="gross_value" id="gross_value" value="R$ 0,00" onchange="calculateNetValue(false)">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="descount_value">Valor Desconto</label>
                            <div class="input-group col-mb-2">
                                <input type="text" class="form-control" name="descount_value" id="descount_value" value="R$ 0,00" onchange="calculateNetValue(false)">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="rounding_value">Valor Arredondamento</label>
                            <div class="input-group col-mb-2">
                                <input type="text" class="form-control" name="rounding_value" id="rounding_value" value="R$ 0,00" onchange="calculateNetValue(false)">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="net_value">Valor Final</label>
                            <div class="input-group col-mb-2">
                                <input type="text" class="form-control" name="net_value" id="net_value" value="R$ 0,00" onchange="calculateNetValue(true)">
                            </div>
                        </div>
                    </div>
                    <br />

                    <div class="card">
                        <div class="card-header">Parcelas</div>
                        <div class="card-body">
                            <label for="installments">Número de Parcelas</label>
                                <input type="number" class="form-control" name="installments" id="installments" value="1" onChange="createInstallment()" required>
                            <br />
                            <div name="installmentsCode" id="installmentsCode">
                            </div>
                        </div>
                    </div>
                    <br />
                    <label for="relevance">Relevância</label>
                    <div class="input-group">
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevance" id="radioUnnecessary" value="0" checked>
                            <label class="form-check-label" for="radioUnnecessary">
                                Dispensável
                            </label>
                        </div>
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevance" id="radioDesirable" value="1">
                            <label class="form-check-label" for="radioDesirable">
                                Desejável
                            </label>
                        </div>
                        <div class="form-check col-md-4">
                            <input class="form-check-input" type="radio" name="relevance" id="radioNecessary" value="2">
                            <label class="form-check-label" for="radioNecessary">
                                Indispensável
                            </label>
                        </div>
                    </div>
                    <br />
                    <label for="description">Descrição</label>
                    <textarea class="form-control" aria-label="With textarea" name="description" id="description"></textarea>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Salvar">
                </div>
            </form>
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($movements as $movement)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $movement->movement_date)->format('d/m/Y') }} | {{ $movement->net_value->formatMoney() }} <br />
                    {{ $movement->title }}
                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Editar" onclick="window.location='';">
                        <form method="post" action=""
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

<script> createInstallment(); </script>
@endsection