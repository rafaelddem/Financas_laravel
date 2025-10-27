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
            CREATE PROCEDURE calculate_wallet_value(IN wallet_id INT)
            BEGIN
                DECLARE sum_in DECIMAL(10,2);
                DECLARE sum_out DECIMAL(10,2);
                DECLARE total DECIMAL(10,2);

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

                SET total = coalesce(sum_in, 0) - coalesce(sum_out, 0);

                SELECT total;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE get_wallet_id_by_invoice(IN invoice_id INT)
            BEGIN
                DECLARE wallet_id INT;

                SELECT wallets.id INTO wallet_id
                FROM invoices
                    JOIN cards ON cards.id = invoices.card_id
                    JOIN wallets ON wallets.id = cards.wallet_id
                WHERE invoices.id = invoice_id;

                CALL calculate_wallet_value(wallet_id);
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS get_wallet_id_by_invoice;");
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_wallet_value;");
    }
};
