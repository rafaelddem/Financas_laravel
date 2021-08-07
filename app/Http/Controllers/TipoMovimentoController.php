<?php

namespace App\Http\Controllers;

use App\Http\Requests\TipoMovimentoRequest;
use App\Models\TipoMovimento;
use Illuminate\Http\Request;

class TipoMovimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tiposMovimento = TipoMovimento::all();
        $tipoMovimento = TipoMovimento::find($request->id);
        return view('tipoMovimento.index', compact('tiposMovimento', 'tipoMovimento'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TipoMovimentoRequest $request)
    {
        $tipoMovimento = new TipoMovimento();
        $tipoMovimento->nome = $request->nome;
        $tipoMovimento->relevancia = $request->relevancia;
        $tipoMovimento->ativo = boolval($request->ativo);
        $tipoMovimento->save();
        $tiposMovimento = TipoMovimento::all();

        $mensagem = 'Registro criado com sucesso';

        return view('tipoMovimento.index', compact('tiposMovimento', 'mensagem'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TipoMovimento  $tipoMovimento
     * @return \Illuminate\Http\Response
     */
    public function show(TipoMovimento $tipoMovimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TipoMovimento  $tipoMovimento
     * @return \Illuminate\Http\Response
     */
    public function edit(TipoMovimento $tipoMovimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TipoMovimento  $tipoMovimento
     * @return \Illuminate\Http\Response
     */
    public function update(TipoMovimentoRequest $request)
    {
        $tipoMovimento = TipoMovimento::find($request->id);
        $tipoMovimento->nome = $request->nome;
        $tipoMovimento->relevancia = $request->relevancia;
        $tipoMovimento->ativo = boolval($request->ativo);
        $tipoMovimento->update();
        $tiposMovimento = TipoMovimento::all();

        $mensagem = 'Registro atualizado com sucesso';

        return view('tipoMovimento.index', compact('tiposMovimento', 'mensagem'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoMovimento  $tipoMovimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $tipoMovimento = TipoMovimento::find($id);
        $tipoMovimento->delete($id);
        $tiposMovimento = TipoMovimento::all();

        $mensagem = 'Registro excluído com sucesso';

        return view('tipoMovimento.index', compact('tiposMovimento', 'mensagem'));
    }
}
