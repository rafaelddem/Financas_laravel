<?php

use App\Enums\Relevance;
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
        Schema::create('extract_imports', function (Blueprint $table) {
            $table->increments('id');
            $table->string('file_name', 100)->nullable(false);
            $table->boolean('ready')->nullable(false)->default(false);
            $table->string('title', 50)->nullable(false);
            $table->date('transaction_date')->nullable(false);
            $table->date('processing_date')->nullable(false);
            $table->integer('category_id')->unsigned()->nullable(false);
            $table->enum('relevance', Relevance::values())->nullable(false);
            $table->integer('payment_method_id')->unsigned()->nullable(false);
            $table->integer('source_wallet_id')->unsigned()->nullable(false);
            $table->integer('destination_wallet_id')->unsigned()->nullable(false);
            $table->integer('card_id')->unsigned()->nullable(true);
            $table->decimal('gross_value', 8, 2);
            $table->decimal('discount_value', 8, 2)->default(0.0);
            $table->decimal('interest_value', 8, 2)->default(0.0);
            $table->decimal('rounding_value', 8, 2)->default(0.0);
            $table->string('description', 255)->nullable();

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
        Schema::dropIfExists('extract_imports');
    }
};
