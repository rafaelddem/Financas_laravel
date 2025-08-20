<?php

namespace Database\Seeders;

use App\Enums\PaymentType;
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
                'type' => PaymentType::notes->name,
            ],
            [
                'id' => 2,
                'name' => 'Transação bancária',
                'type' => PaymentType::transfer->name,
            ],
            [
                'id' => 3,
                'name' => 'Cartão débito',
                'type' => PaymentType::debit->name,
            ],
            [
                'id' => 4,
                'name' => 'Cartão crédito',
                'type' => PaymentType::credit->name,
            ],
        ]);
    }
}
