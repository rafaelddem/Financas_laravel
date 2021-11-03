<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstallmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('installments', function (Blueprint $table) {
            $table->integer('movement')->unsigned();
            $table->integer('installment_number');
            $table->dateTime('duo_date');
            $table->dateTime('payment_date')->nullable();
            $table->decimal('gross_value');
            $table->decimal('descount_value');
            $table->decimal('interest_value');
            $table->decimal('rounding_value');
            $table->decimal('net_value');
            $table->integer('payment_method')->nullable()->unsigned();
            $table->integer('source_wallet')->nullable()->unsigned();
            $table->integer('destination_wallet')->unsigned();
            $table->timestamps();

            $table->foreign('movement')->references('id')->on('movements');
            $table->foreign('payment_method')->references('id')->on('payment_methods');
            $table->foreign('source_wallet')->references('id')->on('wallets');
            $table->foreign('destination_wallet')->references('id')->on('wallets');

            $table->primary(array('movement', 'installment_number'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('installments');
    }
}
