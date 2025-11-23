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
            CREATE FUNCTION calculate_wallet_value(wallet_id INT, start_date DATE, end_date DATE)
            RETURNS DECIMAL(10,2)
            DETERMINISTIC
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

                RETURN total;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE calculate_wallet_value_by_invoice(IN invoice_id INT, start_date DATE, end_date DATE)
            BEGIN
                DECLARE wallet_id INT;

                IF start_date IS NULL THEN
                    SELECT DATE_FORMAT(MIN(processing_date), '%Y-%m-01') INTO start_date FROM transactions;
                END IF;

                IF end_date IS NULL THEN
                    SELECT NOW() INTO end_date;
                END IF;

                SELECT wallets.id INTO wallet_id
                FROM invoices
                    JOIN cards ON cards.id = invoices.card_id
                    JOIN wallets ON wallets.id = cards.wallet_id
                WHERE invoices.id = invoice_id;

                SELECT calculate_wallet_value(wallet_id, start_date, end_date) as total;
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE calculate_all_wallets_value(start_date DATE, end_date DATE)
            BEGIN
                DECLARE done INT DEFAULT FALSE;
                DECLARE wallet_id INT;
                DECLARE wallet_name VARCHAR(100);
                DECLARE wallet_owner_id INT;
                DECLARE wallet_value DECIMAL(10,2);

                DECLARE cur CURSOR FOR SELECT id, name, owner_id FROM wallets where active = 1;
                DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

                IF start_date IS NULL THEN
                    SELECT DATE_FORMAT(MIN(processing_date), '%Y-%m-01') INTO start_date FROM transactions;
                END IF;

                IF end_date IS NULL THEN
                    SELECT NOW() INTO end_date;
                END IF;

                CREATE TEMPORARY TABLE IF NOT EXISTS temporary_wallets (
                    id INT,
                    owner_id INT,
                    name VARCHAR(100),
                    value DECIMAL(10,2)
                );

                DELETE FROM temporary_wallets;

                OPEN cur;

                read_loop: LOOP
                    FETCH cur INTO wallet_id, wallet_name, wallet_owner_id;

                    IF done THEN
                        LEAVE read_loop;
                    END IF;

                    SET wallet_value = calculate_wallet_value(wallet_id, start_date, end_date);
                    INSERT INTO temporary_wallets (id, name, owner_id, value) VALUES (wallet_id, wallet_name, wallet_owner_id, wallet_value);
                END LOOP;

                CLOSE cur;

                SELECT * FROM temporary_wallets;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_all_wallets_value;");
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_wallet_value_by_invoice;");
        DB::unprepared("DROP FUNCTION IF EXISTS calculate_wallet_value;");
    }
};
