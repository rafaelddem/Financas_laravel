<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50);
            $table->integer('installments');
            $table->dateTime('movement_date');
            $table->integer('movement_type')->unsigned();
            $table->decimal('gross_value', 8, 2)->default(0);
            $table->decimal('descount_value', 6, 2)->nullable()->default(0);
            $table->decimal('rounding_value', 5, 2)->nullable()->default(0);
            $table->decimal('net_value', 8, 2)->default(0);
            $table->enum('relevance', [0, 1, 2])->default(0);
            $table->string('description', 255)->nullable();
            $table->timestamps();

            $table->foreign('movement_type')->references('id')->on('movement_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movements');
    }
}
