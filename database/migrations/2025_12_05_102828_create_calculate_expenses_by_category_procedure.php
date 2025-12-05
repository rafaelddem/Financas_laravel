<?php

use Illuminate\Database\Migrations\Migration;

class CreateCalculateExpensesByCategoryProcedure extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared("
            CREATE PROCEDURE calculate_expenses_by_category(admin_id INT, start_date DATE, end_date DATE)
            BEGIN
                select 
                    period, id, name, sum(net_value) as net_value
                from (
                    select 
                        categories.id, 
                        categories.name, 
                        DATE_FORMAT(transactions.processing_date, '%Y-%m') as period, 
                        sum(transactions.gross_value - transactions.discount_value + transactions.interest_value + transactions.rounding_value) as net_value 
                    from 
                        transactions 
                        left join installments on installments.transaction_id = transactions.id
                        left join categories on categories.id = transactions.category_id
                        left join wallets as source_wallet on source_wallet.id = transactions.source_wallet_id
                        left join wallets as destination_wallet on destination_wallet.id = transactions.destination_wallet_id
                        left join payment_methods on payment_methods.id = transactions.payment_method_id
                    where 
                        source_wallet.owner_id = admin_id 
                        and source_wallet.owner_id != destination_wallet.owner_id 
                        and transactions.category_id not in (4, 5) 
                        and transactions.processing_date between start_date and end_date 
                        and payment_methods.type != 'credit'
                    group by 
                        categories.id, categories.name, period

                    union all

                    select 
                        categories.id, 
                        categories.name, 
                        DATE_FORMAT(installments.installment_date, '%Y-%m') as period, 
                        sum(installments.gross_value - installments.discount_value + installments.interest_value + installments.rounding_value) as net_value 
                    from 
                        transactions 
                        left join installments on installments.transaction_id = transactions.id
                        left join categories on categories.id = transactions.category_id
                        left join wallets as source_wallet on source_wallet.id = transactions.source_wallet_id
                        left join wallets as destination_wallet on destination_wallet.id = transactions.destination_wallet_id
                        left join payment_methods on payment_methods.id = transactions.payment_method_id
                    where 
                        source_wallet.owner_id = admin_id 
                        and source_wallet.owner_id != destination_wallet.owner_id 
                        and transactions.category_id not in (4, 5) 
                        and installments.installment_date between start_date and end_date 
                        and payment_methods.type = 'credit'
                    group by 
                        categories.id, categories.name, period
                ) as totals
                group by 
                    id, name, period
                order by 
                    period, id;
            END;
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS calculate_expenses_by_category;");
    }
};
