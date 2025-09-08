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
                'type' => PaymentType::Notes->value,
            ],
            [
                'id' => 2,
                'name' => 'Transação bancária',
                'type' => PaymentType::Transfer->value,
            ],
            [
                'id' => 3,
                'name' => 'Débito (cartão)',
                'type' => PaymentType::Debit->value,
            ],
            [
                'id' => 4,
                'name' => 'Crédito (cartão)',
                'type' => PaymentType::Credit->value,
            ],
        ]);
    }
}
