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
        Schema::create('configs', function (Blueprint $table) {
            $table->string('key', 50)->unique()->nullable(false);
            $table->string('env_key', 50)->unique()->nullable(false);
            $table->string('name', 50)->unique()->nullable(false);
            $table->string('description', 150)->unique()->nullable(false);
            $table->string('value', 50)->nullable(false);
            $table->timestamps();

            $table->primary('key');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
