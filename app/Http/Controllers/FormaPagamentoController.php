<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormaPagamentoRequest;
use App\Models\FormaPagamento;
use Illuminate\Http\Request;

class FormaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $formasPagamento = FormaPagamento::all();
        $formaPagamento = FormaPagamento::find($request->id);
        return view('formaPagamento.index', compact('formasPagamento', 'formaPagamento'));
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
    public function store(FormaPagamentoRequest $request)
    {
        $formaPagamento = new FormaPagamento();
        $formaPagamento->nome = $request->nome;
        $formaPagamento->ativo = boolval($request->ativo);
        $formaPagamento->save();
        $formasPagamento = FormaPagamento::all();

        $mensagem = 'Registro criado com sucesso';

        return view('FormaPagamento.index', compact('formasPagamento', 'mensagem'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\FormaPagamento  $formaPagamento
     * @return \Illuminate\Http\Response
     */
    public function show(FormaPagamento $formaPagamento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\FormaPagamento  $formaPagamento
     * @return \Illuminate\Http\Response
     */
    public function edit(FormaPagamento $formaPagamento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\FormaPagamento  $formaPagamento
     * @return \Illuminate\Http\Response
     */
    public function update(FormaPagamentoRequest $request)
    {
        $formaPagamento = FormaPagamento::find($request->id);
        $formaPagamento->nome = $request->nome;
        $formaPagamento->ativo = boolval($request->ativo);
        $formaPagamento->update();
        $formasPagamento = FormaPagamento::all();

        $mensagem = 'Registro atualizado com sucesso';

        return view('formaPagamento.index', compact('formasPagamento', 'mensagem'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormaPagamento  $formaPagamento
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        $formaPagamento = FormaPagamento::find($id);
        $formaPagamento->delete($id);
        $formasPagamento = FormaPagamento::all();

        $mensagem = 'Registro excluído com sucesso';

        return view('formaPagamento.index', compact('formasPagamento', 'mensagem'));
    }
}
