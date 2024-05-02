            <form method="post" action=" {{route('createWallet')}} ">
                @csrf
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <br />
                    <label for="owner_id">Pertencente a:</label>
                    <select class="form-select" aria-label=".form-select-sm example" name="owner_id" id="owner_id">
                        @foreach($owners as $owner)
                            <option value="{{ $owner->id }}">{{ $owner->name }}</option>
                        @endforeach
                    </select>
                    <br />
                    <label for="main_wallet">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" value=1>
                    <br />
                    <label for="active">Ativo</label>
                    <input type="checkbox" name="active" id="active" value=1 checked>
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Adicionar">
                </div>
            </form>
