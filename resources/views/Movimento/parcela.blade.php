@for ($parcela = 1; $parcela <= $parcelas; $parcela++)
    <div class='input-group'>
        <div class='input-group-text col-md-3'>Parcela 1({{ $parcela }})</div>
        <div class='form-group col-md-4'>
            <input type='text' class='form-control' id='valorInicialParcela1' value='{{ $parcelas }}'>
        </div>
        <div class='form-group col-md-1'></div>
        <div class='form-group col-md-4'>
            <input type='date' class='form-control' name='dataVencimentoParcela1' id='dataVencimentoParcela1' value="{{ Carbon\Carbon::now()->addMonth(2)->format('Y-m-d') }}">
        </div>
    </div>
    <br />
@endfor