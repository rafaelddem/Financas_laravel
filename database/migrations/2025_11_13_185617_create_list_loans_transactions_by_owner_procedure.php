<?php

use Illuminate\Database\Migrations\Migration;

class CreateListLoansTransactionsByOwnerProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            DROP PROCEDURE IF EXISTS list_loans_transactions_by_owner;

            CREATE PROCEDURE list_loans_transactions_by_owner(owner_id INT, start_date DATE, end_date DATE)
            BEGIN
                IF start_date IS NULL THEN
                    SELECT DATE_FORMAT(MIN(processing_date), '%Y-%m-01') INTO start_date FROM transactions;
                END IF;

                IF end_date IS NULL THEN
                    SELECT MAX(processing_date) INTO end_date FROM transactions;
                END IF;

                SELECT 
                    transactions.id as transactions_id, 
                    transactions.title as transactions_title, 
                    installments.installment_number as installment_number, 
                    installments.installment_total as installment_total, 
                    source_owner.id as source_owner_id, 
                    source_owner.name as source_owner_name, 
                    destination_owner.id as destination_owner_id, 
                    destination_owner.name as destination_owner_name, 
                    payment_methods.id as payment_methods_id, 
                    payment_methods.type as payment_methods_type, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN installments.installment_date 
                        WHEN payment_methods.type != 'credit' THEN transactions.processing_date 
                    END as date, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN sum(installments.gross_value) 
                        WHEN payment_methods.type != 'credit' THEN sum(transactions.gross_value) 
                    END as gross_value, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN sum(installments.discount_value) 
                        WHEN payment_methods.type != 'credit' THEN sum(transactions.discount_value) 
                    END as discount_value, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN sum(installments.interest_value) 
                        WHEN payment_methods.type != 'credit' THEN sum(transactions.interest_value) 
                    END as interest_value, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN sum(installments.rounding_value) 
                        WHEN payment_methods.type != 'credit' THEN sum(transactions.rounding_value) 
                    END as rounding_value, 
                    CASE 
                        WHEN payment_methods.type  = 'credit' THEN sum(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value) 
                        WHEN payment_methods.type != 'credit' THEN sum(transactions.gross_value - transactions.discount_value + transactions.interest_value + transactions.rounding_value) 
                    END as net_value 
                FROM transactions 
                    JOIN payment_methods ON payment_methods.id = transactions.payment_method_id
                    LEFT JOIN installments ON installments.transaction_id = transactions.id
                    LEFT JOIN wallets AS source_wallet ON source_wallet.id = transactions.source_wallet_id
                    LEFT JOIN wallets AS destination_wallet ON destination_wallet.id = transactions.destination_wallet_id
                    LEFT JOIN owners AS source_owner ON source_owner.id = source_wallet.owner_id
                    LEFT JOIN owners AS destination_owner ON destination_owner.id = destination_wallet.owner_id
                where
                    (
                        (payment_methods.type  = 'credit' and installments.installment_date between start_date and end_date)
                        or 
                        (payment_methods.type != 'credit' and transactions.processing_date between start_date and end_date)
                    )
                    and 
                    (
                        (source_owner.id = owner_id or destination_owner.id = owner_id)
                        and source_owner.id != destination_owner.id
                    )
                group by
                    transactions.id, transactions.title, source_owner.id, source_owner.name, destination_owner.id, destination_owner.name, payment_methods.id, payment_methods.type, installments.installment_date, transactions.processing_date, installments.installment_number, installments.installment_total 
                order by
                    processing_date, installment_date, transactions_id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS list_loans_transactions_by_owner;");
    }
};
