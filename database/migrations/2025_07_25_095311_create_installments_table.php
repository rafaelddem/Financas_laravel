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
        Schema::create('installments', function (Blueprint $table) {
            $table->integer('transaction_id')->unsigned()->nullable(false);
            $table->integer('installment_number')->unsigned()->nullable(false);
            $table->integer('installment_total')->unsigned()->nullable(false);
            $table->date('installment_date')->nullable(false);
            $table->decimal('gross_value', 8, 2);
            $table->decimal('discount_value', 8, 2)->default(0.0);
            $table->decimal('interest_value', 8, 2)->default(0.0);
            $table->decimal('rounding_value', 8, 2)->default(0.0);
            $table->date('payment_date')->nullable(true);
            
            $table->primary(['transaction_id', 'installment_number']);
            $table->foreign('transaction_id')->references('id')->on('transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
