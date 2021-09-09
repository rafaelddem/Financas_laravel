<?php

namespace App\Http\Controllers;

use App\Http\Requests\MovimentoRequest;
use App\Models\Movimento;
use App\Models\Parcela;
use App\Models\Pessoa;
use App\Models\TipoMovimento;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // registros
        $movimentos = Movimento::query()
            ->select([
                'id',
                'dataMovimento',
                'valorFinal',
                'tipoMovimento',
            ])->get();

        // combobox
        $tipoMovimentos = TipoMovimento::query()
            ->select([
                'id',
                'nome',
            ])
            ->where('ativo', '=', true)
            ->get();
        $pessoas = Pessoa::query()
            ->where('ativo', '=', true)
            ->get();
        $carteirasSistema = [];
        $carteirasPessoais = [];
        $carteirasTerceiros = new Collection();
        foreach ($pessoas as $pessoa) {
            switch ($pessoa->usuarioReferente()) {
                case 0:
                    $carteirasSistema = $pessoa->carteiras;
                    break;
                case 1:
                    $carteirasPessoais = $pessoa->carteiras;
                    break;
                default:
                    $carteirasTerceiros = $pessoa->carteiraPrincipal();
                    // $carteirasTerceiros[$pessoa->carteiraPrincipal()->id] = $pessoa->nome;
                    break;
            }
        }

        return view('movimento.index', compact('movimentos', 'tipoMovimentos', 'carteirasSistema', 'carteirasPessoais', 'carteirasTerceiros'));
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
    public function store(MovimentoRequest $request)
    {
        try {
            DB::beginTransaction();

            $movimento = new Movimento();
            $movimento->numeroParcelas = $request->numeroParcelas;
            $movimento->dataMovimento = $request->dataMovimento;
            $movimento->tipoMovimento = $request->tipoMovimento;
            $movimento->valorInicial = $request->valorInicial;
            $movimento->valorDesconto = $request->valorDesconto;
            $movimento->valorArredondamento = $request->valorArredondamento;
            $movimento->valorFinal = $request->valorFinal;
            $movimento->relevancia = $request->relevancia;
            $movimento->descricao = $request->descricao;
            $movimento->save();

            for ($parcela = 0; $parcela < $request->numeroParcelas; $parcela++) { 
                $dataVencimentoParcela = $request->dataVencimentoParcela[$parcela]; 
                $valorInicialParcela = $request->valorInicialParcela[$parcela];

                $novaParcela = new Parcela();
                $novaParcela->movimento = $movimento->id;
                $novaParcela->parcela = $parcela + 1;
                $novaParcela->dataVencimento = $dataVencimentoParcela;
                // $novaParcela->dataPagamento = $request->dataPagamento;
                $novaParcela->valorInicial = $valorInicialParcela;
                $novaParcela->valorDesconto = 0;
                $novaParcela->valorJuros = 0;
                $novaParcela->valorArredondamento = 0;
                $novaParcela->valorFinal = $valorInicialParcela;
                // $novaParcela->formaPagamento = $request->formaPagamento;
                $novaParcela->carteiraOrigem = $request->carteiraOrigem;
                $novaParcela->carteiraDestino = $request->carteiraDestino;
                $novaParcela->save();
            }

            DB::commit();
            $mensagem = 'Registro salvo com sucesso';
        } catch (\Throwable $th) {
            DB::rollBack();
            $mensagem = 'Erro ao salvar o movimento'.' | '.$th->getMessage();
        }

        // registros
        $movimentos = Movimento::query()->with('tipoMovimento')->get();

        // combobox
        $tipoMovimentos = TipoMovimento::query()->where('ativo', true)->get(['id', 'nome']);
        $pessoas = Pessoa::query()->where('ativo', '=', true)->get();
        $carteirasSistema = [];
        $carteirasPessoais = [];
        $carteirasTerceiros = new Collection();
        foreach ($pessoas as $pessoa) {
            switch ($pessoa->usuarioReferente()) {
                case 0:
                    $carteirasSistema = $pessoa->carteiras;
                    break;
                case 1:
                    $carteirasPessoais = $pessoa->carteiras;
                    break;
                default:
                    $carteirasTerceiros[$pessoa->carteiraPrincipal()->id] = $pessoa->nome;
                    break;
            }
        }

        return view('movimento.index', compact('movimentos', 'tipoMovimentos', 'carteirasSistema', 'carteirasPessoais', 'carteirasTerceiros', 'mensagem'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function show(Movimento $movimento)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function edit(Movimento $movimento)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Movimento $movimento)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Movimento  $movimento
     * @return \Illuminate\Http\Response
     */
    public function destroy(Movimento $movimento)
    {
        //
    }
}
