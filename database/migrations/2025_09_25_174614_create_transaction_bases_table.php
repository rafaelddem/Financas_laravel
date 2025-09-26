<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaction_bases', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->nullable(false);
            $table->integer('category_id')->unsigned()->nullable(false);
            $table->integer('payment_method_id')->unsigned()->nullable(false);
            $table->integer('card_id')->unsigned()->nullable(true);
            $table->integer('source_wallet_id')->unsigned()->nullable(false);
            $table->integer('destination_wallet_id')->unsigned()->nullable(false);

            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods');
            $table->foreign('card_id')->references('id')->on('cards');
            $table->foreign('source_wallet_id')->references('id')->on('wallets');
            $table->foreign('destination_wallet_id')->references('id')->on('wallets');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_bases');
    }
};
