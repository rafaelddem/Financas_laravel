<?php

use Illuminate\Database\Migrations\Migration;

class CreateCalculateWalletValueProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE calculate_wallet_value(IN invoice_id INT)
            BEGIN
                DECLARE wallet_id INTEGER;
                DECLARE sum_in DECIMAL(10,2);
                DECLARE sum_out DECIMAL(10,2);
                DECLARE total DECIMAL(10,2);

                select wallets.id INTO wallet_id
                from invoices
                    join cards on cards.id = invoices.card_id
                    join wallets on wallets.id = cards.wallet_id
                where invoices.id = invoice_id;

                select sum(gross_value - discount_value + interest_value + rounding_value) INTO sum_in
                from transactions
                    join wallets on wallets.id = transactions.destination_wallet_id
                    join payment_methods on payment_methods.id = transactions.payment_method_id
                where
                    payment_methods.type != 'credit'
                    and transactions.destination_wallet_id = wallet_id;

                select sum(gross_value - discount_value + interest_value + rounding_value) INTO sum_out
                from transactions
                    join wallets on wallets.id = transactions.source_wallet_id
                    join payment_methods on payment_methods.id = transactions.payment_method_id
                where
                    payment_methods.type != 'credit'
                    and transactions.source_wallet_id = wallet_id;

                SET total = sum_in - sum_out;

                SELECT total;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_wallet_value;");
    }
};
