<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionBase extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_bases')->insert([
            [
                'title' => __('Invoice Payment Transaction Title'),
                'category_id' => 4,
                'payment_method_id' => 2,
                'source_wallet_id' => env('MY_OWNER_ID'),
                'destination_wallet_id' => 1,
            ],
        ]);
    }
}
