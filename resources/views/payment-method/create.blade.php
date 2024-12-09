            <form method="post" action=" {{route('payment-method.store')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="type">Tipo:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="type" id="type">
                        <option value="0">Cédulas e/ou Moedas</option>
                        <option value="1">Transações Bancárias</option>
                        <option value="2">Débito</option>
                        <option value="3">Crédito</option>
                    </select>
                    <br />
                    <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
                </div>
            </form>
