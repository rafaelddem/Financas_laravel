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
        // id:   1  | owner: Não definida
        DB::table('wallets')->insert([
            'name' => 'Entradas', 
            'owner' => 1, 
            'active' => true, 
            'main_wallet' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   2  | owner: Não definida
        DB::table('wallets')->insert([
            'name' => 'Saídas', 
            'owner' => 1, 
            'active' => true, 
            'main_wallet' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   3  | owner: Rafael
        DB::table('wallets')->insert([
            'name' => 'Casa', 
            'owner' => 2, 
            'active' => true, 
            'main_wallet' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   4  | owner: Rafael
        DB::table('wallets')->insert([
            'name' => 'NuBank', 
            'owner' => 2, 
            'active' => true, 
            'main_wallet' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   5  | owner: Rafael
        DB::table('wallets')->insert([
            'name' => 'NuConta', 
            'owner' => 2, 
            'active' => true, 
            'main_wallet' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   6  | owner: Terezinha
        DB::table('wallets')->insert([
            'name' => 'Casa', 
            'owner' => 3, 
            'active' => true, 
            'main_wallet' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   7  | owner: Márcio
        DB::table('wallets')->insert([
            'name' => 'Casa', 
            'owner' => 4, 
            'active' => true, 
            'main_wallet' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
