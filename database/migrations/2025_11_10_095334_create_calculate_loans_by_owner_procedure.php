<?php

use Illuminate\Database\Migrations\Migration;

class CreateCalculateLoansByOwnerProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS calculate_loans_by_owner;

            CREATE PROCEDURE calculate_loans_by_owner(start_date DATE, end_date DATE)
            BEGIN

                IF start_date IS NULL THEN
                    SELECT DATE_FORMAT(MIN(processing_date), '%Y-%m-01') INTO start_date FROM transactions;
                END IF;

                IF end_date IS NULL THEN
                    SELECT MAX(processing_date) INTO end_date FROM transactions;
                END IF;

                SELECT id, name, SUM(value) AS value
                FROM (
                        SELECT 
                            source_owner.id, 
                            source_owner.name, 
                            SUM(
                                CASE 
                                    WHEN payment_methods.type = 'credit' THEN 
                                        (installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value) * -1
                                    ELSE 
                                        (transactions.gross_value - transactions.discount_value + transactions.interest_value + transactions.rounding_value) * -1
                                END
                            ) AS value
                        FROM transactions 
                            LEFT JOIN payment_methods on payment_methods.id = transactions.payment_method_id
                            LEFT JOIN installments ON installments.transaction_id = transactions.id
                            LEFT JOIN wallets AS source_wallet ON source_wallet.id = transactions.source_wallet_id
                            LEFT JOIN owners AS source_owner ON source_owner.id = source_wallet.owner_id
                        WHERE 
                            (installment_number IS NOT NULL AND installment_date BETWEEN start_date AND end_date) 
                            OR (installment_number IS NULL AND processing_date BETWEEN start_date AND end_date)
                        GROUP BY 
                            source_owner.id, 
                            source_owner.name

                    UNION ALL

                        SELECT 
                            destination_owner.id, 
                            destination_owner.name, 
                            SUM(
                                CASE 
                                    WHEN payment_methods.type = 'credit' THEN 
                                        (installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value) 
                                    ELSE 
                                        (transactions.gross_value - transactions.discount_value + transactions.interest_value + transactions.rounding_value) 
                                END
                            ) AS value
                        FROM transactions 
                            LEFT JOIN payment_methods on payment_methods.id = transactions.payment_method_id
                            LEFT JOIN installments ON installments.transaction_id = transactions.id
                            LEFT JOIN wallets AS destination_wallet ON destination_wallet.id = transactions.destination_wallet_id
                            LEFT JOIN owners AS destination_owner ON destination_owner.id = destination_wallet.owner_id
                        WHERE 
                            (installment_number IS NOT NULL AND installment_date BETWEEN start_date AND end_date) 
                            OR (installment_number IS NULL AND processing_date BETWEEN start_date AND end_date)
                        GROUP BY 
                            destination_owner.id, 
                            destination_owner.name
                ) AS loans
                GROUP BY id, name;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_loans_by_owner;");
    }
};
