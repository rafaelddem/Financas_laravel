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
                    SELECT NOW() INTO end_date;
                END IF;

                SELECT id, name, SUM(value) AS value
                FROM (
                    select source_owner.id, source_owner.name, sum((gross_value - discount_value + interest_value + rounding_value)) * -1 as value
                    from transactions 
                        left join wallets as source_wallet on source_wallet.id = source_wallet_id
                        left join owners as source_owner on source_owner.id = source_wallet.owner_id
                    where processing_date between start_date and end_date
                    group by source_owner.id, source_owner.name 
                    UNION ALL
                    select destination_owner.id, destination_owner.name, sum((gross_value - discount_value + interest_value + rounding_value)) as value
                    from transactions 
                        left join wallets as destination_wallet on destination_wallet.id = destination_wallet_id
                        left join owners as destination_owner on destination_owner.id = destination_wallet.owner_id
                    where processing_date between start_date and end_date
                    group by destination_owner.id, destination_owner.name 
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
