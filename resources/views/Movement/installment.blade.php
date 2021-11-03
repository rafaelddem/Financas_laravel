@extends('layout')

@section('header')
Pagamento
@endsection

@section('content')
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
            <form method="post" action="{{route('listInstallment')}}">
                @csrf
                @if ($installment !== null)
                <div class="container">
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="duo_date">Data Vencimento</label>
                            <div class="input-group col-mb-2"><input type="date" class="form-control" name="duo_date" id="duo_date" value="{{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $installment->duo_date)->format('Y-m-d') }}" disabled>
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="payment_date">Data Pagamento</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="date" class="form-control" name="payment_date" id="payment_date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}">
                            </div>
                        </div>
                    </div>
                    <br />
                    <label for="title">Título</label>
                    <input type="text" class="form-control" name="title" id="title" value="{{ $installment }}" disabled>
                    <br />
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="gross_value">Valor Inicial</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="gross_value" id="gross_value" value="{{ $installment->gross_value }}" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="descount_value">Valor Desconto</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="descount_value" id="descount_value" value="{{ $installment->descount_value }}" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="interest_value">Juros</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="interest_value" id="interest_value" value="{{ $installment->interest_value }}" step="any" onchange="calcularValorArredondamento()">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-5">
                            <label for="rounding_value">Valor Arredondamento</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="rounding_value" id="rounding_value" value="{{ $installment->rounding_value }}" step="any" onchange="calcularValorFinal()">
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="input-group">
                        <div class="form-group col-md-5">
                            <label for="net_value">Valor Final</label>
                            <div class="input-group col-mb-2">
                                <div class="input-group-prepend"><div class="input-group-text">R$</div></div>
                                <input type="number" class="form-control" name="net_value" id="net_value" value="{{ $installment->net_value }}" step="any" onchange="calcularValorArredondamento()">
                            </div>
                        </div>
                        <div class="form-group col-md-2"></div>
                        <div class="form-group col-md-2"></div>
                    </div>
                    <br />
                    <label for="payment_method">Forma de Pagamento:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="payment_method" id="payment_method">
                        <option value="0">Selecione...</option>
                        @foreach($paymentMethods as $paymentMethod)
                            <option value="{{ $paymentMethod->id }}">{{ $paymentMethod->name }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="source_wallet">Origem:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="source_wallet" id="source_wallet">
                        <optgroup label="Genéricas">
                        @foreach($systemWallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($personalWallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($thirdPartyWallet as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->ownerName() }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />
                    <label for="destination_wallet">Destino:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="destination_wallet" id="destination_wallet">
                        <optgroup label="Genéricas">
                        @foreach($systemWallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Pessoais">
                        @foreach($personalWallets as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                        @endforeach
                        </optgroup>
                        <optgroup label="Terceiros">
                        @foreach($thirdPartyWallet as $wallet)
                            <option value="{{ $wallet->id }}">{{ $wallet->ownerName() }}</option>
                        @endforeach
                        </optgroup>
                    </select>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Salvar">
                </div>
                @endif
            </form>
        </div>
        <div class="col">
            <ul class="list-group">
                @foreach($installments as $installment)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $installment->duo_date)->format('d/m/Y') }} | R$ {{ $installment->net_value }}
                    <span class="d-flex">
                        <input class="btn btn-primary" type="button" value="Pagar" onclick="window.location='{{route('findInstallment', [$installment->movement, $installment->installment_number])}}';">
                        <form method="post" action="{{route('deleteInstallment', $installment->id)}}"
                            onsubmit="return confirm('Tem certeza que deseja remover este parcela?')">
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