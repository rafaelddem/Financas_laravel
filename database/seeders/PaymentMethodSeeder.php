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
                'type' => 0,
            ],
            [
                'id' => 2,
                'name' => 'Transação',
                'type' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Cartão crédito',
                'type' => 3,
            ],
            [
                'id' => 4,
                'name' => 'Cartão débito',
                'type' => 2,
            ],
        ]);
    }
}
