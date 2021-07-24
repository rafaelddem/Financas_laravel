<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pessoa;

class PessoaController extends Controller
{
    public function index(Request $request)
    {
        $pessoas = Pessoa::all();
        $pessoa = Pessoa::find($request->id);
        return view('pessoa.index', compact('pessoas', 'pessoa'));
    }

    public function store(Request $request)
    {
        $pessoa = new Pessoa();
        $pessoa->nome = $request->nome;
        $pessoa->ativo = boolval($request->ativo);
        $pessoa->save();
        $pessoas = Pessoa::all();

        return view('pessoa.index', compact('pessoas'));
    }

    public function update(Request $request)
    {
        $pessoa = Pessoa::find($request->id);
        $pessoa->nome = $request->nome;
        $pessoa->ativo = boolval($request->ativo);
        $pessoa->update();
        $pessoas = Pessoa::all();

        return view('pessoa.index', compact('pessoas'));
    }

    public function destroy(Request $request)
    {
        $pessoa = Pessoa::find($request->id);
        $pessoa->delete($request->id);
        $pessoas = Pessoa::all();

        return view('pessoa.index', compact('pessoas'));
    }
}
