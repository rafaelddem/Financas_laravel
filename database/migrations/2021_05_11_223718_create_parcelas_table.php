<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcelas', function (Blueprint $table) {
            $table->integer('movimento')->unsigned();
            $table->integer('parcela');
            $table->dateTime('dataVencimento');
            $table->dateTime('dataPagamento');
            $table->decimal('valorInicial');
            $table->decimal('valorDesconto');
            $table->decimal('valorJuros');
            $table->decimal('valorArredondamento');
            $table->decimal('valorFinal');
            $table->integer('formaPagamento')->unsigned();
            $table->integer('carteiraOrigem')->unsigned();
            $table->integer('carteiraDestino')->unsigned();

            $table->foreign('movimento')->references('id')->on('movimentos');
            $table->foreign('formaPagamento')->references('id')->on('forma_pagamentos');
            $table->foreign('carteiraOrigem')->references('id')->on('carteiras');
            $table->foreign('carteiraDestino')->references('id')->on('carteiras');

            $table->primary(array('movimento', 'parcela'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parcelas');
    }
}
