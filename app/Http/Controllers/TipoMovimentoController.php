<?php

namespace App\Http\Controllers;

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
    public function store(Request $request)
    {
        $tipoMovimento = new TipoMovimento();
        $tipoMovimento->nome = $request->nome;
        $tipoMovimento->indispensavel = $request->indispensavel;
        $tipoMovimento->ativo = boolval($request->ativo);
        $tipoMovimento->save();
        $tiposMovimento = TipoMovimento::all();

        return view('tipoMovimento.index', compact('tiposMovimento'));
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
    public function update(Request $request)
    {
        $tipoMovimento = TipoMovimento::find($request->id);
        $tipoMovimento->nome = $request->nome;
        $tipoMovimento->indispensavel = $request->indispensavel;
        $tipoMovimento->ativo = boolval($request->ativo);
        $tipoMovimento->update();
        $tiposMovimento = TipoMovimento::all();

        return view('tipoMovimento.index', compact('tiposMovimento'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TipoMovimento  $tipoMovimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $tipoMovimento = TipoMovimento::find($request->id);
        $tipoMovimento->delete($request->id);
        $tiposMovimento = TipoMovimento::all();

        return view('tipoMovimento.index', compact('tiposMovimento'));
    }
}
