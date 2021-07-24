<?php

namespace App\Http\Controllers;

use App\Models\Carteira;
use App\Models\Pessoa;
use Illuminate\Http\Request;

class CarteiraController extends Controller
{
    public function index(Request $request)
    {
        $carteira = Carteira::find($request->id);

        $carteiras = Carteira::join('pessoas', 'pessoas.id', 'carteiras.pessoa')->select('carteiras.*', 'pessoas.nome as dono')->get();
        $pessoas = Pessoa::all();

        return view('carteira.index', compact('carteiras', 'pessoas', 'carteira'));
    }

    public function store(Request $request)
    {
        $carteira = new Carteira();
        $carteira->nome = $request->nome;
        $carteira->pessoa = $request->pessoa;
        $carteira->ativo = boolval($request->ativo);
        $carteira->save();

        $carteiras = Carteira::join('pessoas', 'pessoas.id', 'carteiras.pessoa')->select('carteiras.*', 'pessoas.nome as dono')->get();
        $pessoas = Pessoa::all();

        return view('carteira.index', compact('carteiras', 'pessoas'));
    }

    public function update(Request $request)
    {
        $carteira = Carteira::find($request->id);
        $carteira->nome = $request->nome;
        $carteira->pessoa = $request->pessoa;
        $carteira->ativo = boolval($request->ativo);
        $carteira->update();

        $carteiras = Carteira::join('pessoas', 'pessoas.id', 'carteiras.pessoa')->select('carteiras.*', 'pessoas.nome as dono')->get();
        $pessoas = Pessoa::all();

        return view('carteira.index', compact('carteiras', 'pessoas'));
    }

    public function destroy(Request $request)
    {
        $carteira = Carteira::find($request->id);
        $carteira->delete($request->id);

        $carteiras = Carteira::join('pessoas', 'pessoas.id', 'carteiras.pessoa')->select('carteiras.*', 'pessoas.nome as dono')->get();
        $pessoas = Pessoa::all();

        return view('carteira.index', compact('carteiras', 'pessoas'));
    }
}
