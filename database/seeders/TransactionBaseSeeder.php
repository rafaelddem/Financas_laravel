<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Pagamento de fatura
        DB::table('transaction_bases')->insert([
            [
                'title' => __('Invoice Payment Transaction Title'),
                'category_id' => env('INVOICE_PAYMENT_CATEGORY'),
                'payment_method_id' => env('INVOICE_PAYMENT_METHOD'),
                'source_wallet_id' => env('MY_OWNER_ID'),
                'destination_wallet_id' => env('SYSTEM_ID'),
            ],
        ]);
    }
}
