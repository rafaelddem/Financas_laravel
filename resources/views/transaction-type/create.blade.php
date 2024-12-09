            <form method="post" action=" {{route('transaction-type.store')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome:</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="relevance">Relevância:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="relevance" id="relevance">
                        <option value="0">Banal</option>
                        <option value="1">Relevante</option>
                        <option value="2">Importante</option>
                    </select>
                    <br />
                    <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
                </div>
            </form>
