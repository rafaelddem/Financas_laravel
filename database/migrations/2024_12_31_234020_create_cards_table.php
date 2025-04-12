<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cards', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('wallet_id')->unsigned();
            $table->string('name', 20)->nullable(false);
            $table->enum('card_type', ['debit', 'credit'])->nullable(false);
            $table->integer('first_day_month');
            $table->integer('days_to_expiration');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cards');
    }
}
