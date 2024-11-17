            <form method="post" action="{{route('updateWallet', ['id' => $wallet->id])}}">
                @csrf
                @method('PUT')
                <div class="container">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $wallet->name }}" disabled>
                    <br />
                    <label for="owner_id">Pertencente a:</label>
                    <input type="text" class="form-control" name="name" id="name" value="{{ $wallet->owner->name }}" disabled>
                    <br />
                    <label for="nome">Carteira Principal</label>
                    <input type="checkbox" name="main_wallet" id="main_wallet" value=1 @if ($wallet->main_wallet) checked @endif @if ($wallet->main_wallet) disabled @endif >
                    <br />
                    <label for="nome">Ativo</label>
                    <input type="checkbox" name="active" id="active" value=1 @if ($wallet->active) checked @endif @if ($wallet->main_wallet) disabled @endif >
                    <br />
                    <input class="btn btn-primary mt-2" type="submit" value="Atualizar">
                    <input class="btn btn-primary mt-2" type="button" value="Voltar" onclick="window.location='{{route('listWallet')}}';">
                </div>
            </form>
