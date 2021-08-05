<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movimentos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numeroParcelas');
            $table->dateTime('dataMovimento');
            $table->integer('tipoMovimento')->unsigned();
            $table->decimal('valorInicial', 8, 2)->default(0);
            $table->decimal('valorDesconto', 6, 2)->nullable()->default(0);
            $table->decimal('valorArredondamento', 5, 2)->nullable()->default(0);
            $table->decimal('valorFinal', 8, 2)->default(0);
            $table->enum('relevancia', [0, 1, 2])->default(0);
            $table->string('descricao');
            $table->timestamps();

            $table->foreign('tipoMovimento')->references('id')->on('tipo_movimentos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movimentos');
    }
}
