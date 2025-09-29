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
        Schema::create('extract_modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->nullable(false);
            $table->integer('transaction_base_in_id')->unsigned()->nullable(false);
            $table->integer('transaction_base_out_id')->unsigned()->nullable(false);

            $table->foreign('transaction_base_in_id')->references('id')->on('transaction_bases');
            $table->foreign('transaction_base_out_id')->references('id')->on('transaction_bases');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('extract_modules');
    }
};
