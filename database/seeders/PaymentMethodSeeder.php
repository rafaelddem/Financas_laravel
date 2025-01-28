<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_methods')->insert([
            [
                'id' => 1,
                'name' => 'Dinheiro físico',
                'type' => 'notes',
            ],
            [
                'id' => 2,
                'name' => 'Transação',
                'type' => 'transfer',
            ],
            [
                'id' => 3,
                'name' => 'Cartão débito',
                'type' => 'debit',
            ],
            [
                'id' => 4,
                'name' => 'Cartão crédito',
                'type' => 'credit',
            ],
        ]);
    }
}
