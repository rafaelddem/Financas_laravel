<?php

use Illuminate\Database\Migrations\Migration;

class CreateCalculateIncomeByPeriodProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE calculate_income_until_date(admin_id INT, limit_date DATE)
            BEGIN
                select 
                    COALESCE(SUM(case
                            when source.owner_id = admin_id and destination.owner_id = admin_id then 0
                            when source.owner_id = admin_id and destination.owner_id != admin_id then -(gross_value - discount_value + interest_value + rounding_value)
                            when source.owner_id != admin_id and destination.owner_id = admin_id then (gross_value - discount_value + interest_value + rounding_value)
                        end), 0) as total
                from transactions
                    left join payment_methods on payment_methods.id = transactions.payment_method_id
                    left join wallets as source on source.id = transactions.source_wallet_id
                    left join wallets as destination on destination.id = transactions.destination_wallet_id
                where
                    payment_methods.type != 'credit' and processing_date between '2000-01-01' and limit_date
                    and (source.owner_id = admin_id or destination.owner_id = admin_id);
            END;
        ");

        DB::unprepared("
            CREATE PROCEDURE calculate_income_by_period(admin_id INT, start_date DATE, end_date DATE)
            BEGIN
                select 
                    DATE_FORMAT(transactions.processing_date, '%m/%Y') as date, 
                    COALESCE(SUM(case
                            when source.owner_id = admin_id and destination.owner_id = admin_id then 0
                            when source.owner_id = admin_id and destination.owner_id != admin_id then -(gross_value - discount_value + interest_value + rounding_value)
                            when source.owner_id != admin_id and destination.owner_id = admin_id then (gross_value - discount_value + interest_value + rounding_value)
                        end), 0) as value
                from transactions
                    left join payment_methods on payment_methods.id = transactions.payment_method_id
                    left join wallets as source on source.id = transactions.source_wallet_id
                    left join wallets as destination on destination.id = transactions.destination_wallet_id
                where
                    payment_methods.type != 'credit' and processing_date between start_date and end_date
                    and (source.owner_id = admin_id or destination.owner_id = admin_id)
                group by 
                    DATE_FORMAT(transactions.processing_date, '%m/%Y')
                order by 
                    transactions.processing_date;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_income_until_date;");
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_income_by_period;");
    }
};
