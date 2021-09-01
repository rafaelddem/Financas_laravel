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
                            ->select(['carteiras.id', 'carteiras.nome', 'carteiras.ativo', 'pessoas.nome as dono'])
                            ->join('pessoas', 'pessoas.id', 'carteiras.pessoa')
                            ->orderby('pessoas.id', 'asc')
                            ->orderby('carteiras.principal', 'desc')
                            ->orderby('carteiras.id', 'asc')
                            ->get();

        $pessoas = Pessoa::all();
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

        $carteiras = Carteira::query()
                            ->select(['carteiras.id', 'carteiras.nome', 'carteiras.ativo', 'pessoas.nome as dono'])
                            ->join('pessoas', 'pessoas.id', 'carteiras.pessoa')
                            ->orderby('pessoas.id', 'asc')
                            ->orderby('carteiras.principal', 'desc')
                            ->orderby('carteiras.id', 'asc')
                            ->get();
        $pessoas = Pessoa::all();

        $mensagem = 'Registro criado com sucesso';

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

        $carteiras = Carteira::query()
                            ->select(['carteiras.id', 'carteiras.nome', 'carteiras.ativo', 'pessoas.nome as dono'])
                            ->join('pessoas', 'pessoas.id', 'carteiras.pessoa')
                            ->orderby('pessoas.id', 'asc')
                            ->orderby('carteiras.principal', 'desc')
                            ->orderby('carteiras.id', 'asc')
                            ->get();
        $pessoas = Pessoa::all();

        $mensagem = 'Registro atualizado com sucesso';

        return view('carteira.index', compact('carteiras', 'pessoas', 'mensagem'));
    }

    public function destroy(int $id)
    {
        $carteira = Carteira::find($id);
        $carteira->delete($id);

        $carteiras = Carteira::join('pessoas', 'pessoas.id', 'carteiras.pessoa')->select('carteiras.*', 'pessoas.nome as dono')->get();
        $pessoas = Pessoa::all();

        $mensagem = 'Registro excluído com sucesso';

        return view('carteira.index', compact('carteiras', 'pessoas', 'mensagem'));
    }
}
