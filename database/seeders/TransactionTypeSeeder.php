<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TransactionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transaction_types')->insert([
            [
                'id' => 1,
                'name' => 'Entradas diversas',
                'relevance' => 'banal',
            ],
            [
                'id' => 2,
                'name' => 'Saídas diversas',
                'relevance' => 'banal',
            ],
            [
                'id' => 3,
                'name' => 'Movimentação entre carteiras',
                'relevance' => 'banal',
            ],
        ]);
    }
}
