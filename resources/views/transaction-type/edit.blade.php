<form method="post" action=" {{route('transaction-type.update')}} ">
    @csrf @method('PUT')
    <div class="container">
        <input type="hidden" name="id" value={{$transactionType->id}}>
        <label for="name">Nome</label>
        <input type="text" class="form-control" name="name" id="name" value="{{$transactionType->name}}" disabled>
        <br />
        <label for="relevance">Relevância:</label>
        <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
            <option value="0" @if ($transactionType->relevance == 0) selected @endif>Banal</option>
            <option value="1" @if ($transactionType->relevance == 1) selected @endif>Relevante</option>
            <option value="2" @if ($transactionType->relevance == 2) selected @endif>Importante</option>
        </select>
        <br />
        <button type="submit" class="btn btn-primary mt-2">Atualizar</button>
    </div>
</form>
