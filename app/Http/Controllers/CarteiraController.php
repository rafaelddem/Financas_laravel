<?php

namespace App\Http\Controllers;

use App\Http\Requests\CarteiraRequest;
use App\Models\Carteira;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class CarteiraController extends Controller
{
    public function index(Request $request)
    {
        $carteira = Carteira::find($request->id);
        $carteiras = Carteira::query()
            ->select([
                'id',
                'nome',
                'pessoa',
                'ativo',
            ])
            ->orderby('pessoa', 'asc')
            ->orderby('principal', 'desc')
            ->orderby('id', 'asc')
            ->get();
        $pessoas = Pessoa::query()
            ->select([
                'id',
                'nome',
            ])
            ->where('ativo', '=', true)
            ->get();
        return view('carteira.index', compact('carteiras', 'pessoas', 'carteira'));
    }

    public function store(CarteiraRequest $request)
    {
        $carteira = new Carteira();
        $carteira->nome = $request->nome;
        $carteira->pessoa = $request->pessoa;
        $carteira->principal = boolval($request->principal);
        $carteira->ativo = boolval($request->ativo);
        $carteira->save();

        $mensagem = 'Registro criado com sucesso';

        $carteiras = Carteira::query()
            ->select([
                'id',
                'nome',
                'pessoa',
                'ativo',
            ])
            ->orderby('pessoa', 'asc')
            ->orderby('principal', 'desc')
            ->orderby('id', 'asc')
            ->get();
        $pessoas = Pessoa::query()
            ->select([
                'id',
                'nome',
            ])
            ->where('ativo', '=', true)
            ->get();

        return view('carteira.index', compact('carteiras', 'pessoas', 'mensagem'));
    }

    public function update(CarteiraRequest $request)
    {
        $carteira = Carteira::find($request->id);
        $carteira->nome = $request->nome;
        $carteira->pessoa = $request->pessoa;
        $carteira->principal = boolval($request->principal);
        $carteira->ativo = boolval($request->ativo);
        $carteira->update();

        $mensagem = 'Registro atualizado com sucesso';

        $carteiras = Carteira::query()
            ->select([
                'id',
                'nome',
                'pessoa',
                'ativo',
            ])
            ->orderby('pessoa', 'asc')
            ->orderby('principal', 'desc')
            ->orderby('id', 'asc')
            ->get();
        $pessoas = Pessoa::query()
            ->select([
                'id',
                'nome',
            ])
            ->where('ativo', '=', true)
            ->get();

        return view('carteira.index', compact('carteiras', 'pessoas', 'mensagem'));
    }

    public function destroy(int $id)
    {
        $carteira = Carteira::find($id);
        $carteira->delete($id);

        $mensagem = 'Registro excluído com sucesso';

        $carteiras = Carteira::query()
            ->select([
                'id',
                'nome',
                'pessoa',
                'ativo',
            ])
            ->orderby('pessoa', 'asc')
            ->orderby('principal', 'desc')
            ->orderby('id', 'asc')
            ->get();
        $pessoas = Pessoa::query()
            ->select([
                'id',
                'nome',
            ])
            ->where('ativo', '=', true)
            ->get();

        return view('carteira.index', compact('carteiras', 'pessoas', 'mensagem'));
    }
}
