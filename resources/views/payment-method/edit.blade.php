<form method="post" action=" {{route('payment-method.update')}} ">
    @csrf @method('PUT')
    <div class="container">
        <input type="hidden" name="id" value={{$paymentMethod->id}}>
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="{{$paymentMethod->name}}" disabled>
        <br />
        <label for="type">Relevância:</label>
        <select class="form-select" aria-label=".form-select-sm example" name="type" id="type">
            <option value="0" @if ($paymentMethod->type == 0) selected @endif>Cédulas e/ou Moedas</option>
            <option value="1" @if ($paymentMethod->type == 1) selected @endif>Transações Bancárias</option>
            <option value="2" @if ($paymentMethod->type == 2) selected @endif>Débito</option>
            <option value="3" @if ($paymentMethod->type == 3) selected @endif>Crédito</option>
        </select>
        <br />
        <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
    </div>
</form>
