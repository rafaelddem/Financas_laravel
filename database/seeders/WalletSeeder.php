<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id:   1  | owner: Sistema
        DB::table('wallets')->insert([
            'id' => 1,
            'name' => 'Origem/Destino Indefinido',
            'owner_id' => 1,
            'main_wallet' => true,
            'description' => 'Carteira utilizada para movimentações de origem indefinida (como recebimento de salário) ou destino indefinido (como pagamento de uma venda)',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // id:   2  | owner: Rafael
        DB::table('wallets')->insert([
            'name' => 'Casa',
            'owner_id' => 2,
            'main_wallet' => true,
            'description' => 'Carteira padrão do usuário',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
