<?php

namespace App\Http\Controllers;

use App\Http\Requests\PessoaRequest;
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

    public function store(PessoaRequest $request)
    {
        $pessoa = new Pessoa();
        $pessoa->nome = $request->nome;
        $pessoa->ativo = boolval($request->ativo);
        $pessoa->save();
        $pessoas = Pessoa::all();

        $mensagem = 'Registro criado com sucesso';

        return view('pessoa.index', compact('pessoas', 'mensagem'));
    }

    public function update(PessoaRequest $request)
    {
        $pessoa = Pessoa::find($request->id);
        $pessoa->nome = $request->nome;
        $pessoa->ativo = boolval($request->ativo);
        $pessoa->update();
        $pessoas = Pessoa::all();

        $mensagem = 'Registro atualizado com sucesso';

        return view('pessoa.index', compact('pessoas', 'mensagem'));
    }

    public function destroy(int $id)
    {
        $pessoa = Pessoa::find($id);
        $pessoa->delete($id);
        $pessoas = Pessoa::all();

        $mensagem = 'Registro excluído com sucesso';

        return view('pessoa.index', compact('pessoas', 'mensagem'));
    }
}
