<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FormaPagamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('forma_pagamentos')->insert([
            'nome' => 'Dinheiro', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('forma_pagamentos')->insert([
            'nome' => 'Crédito', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 3
        DB::table('forma_pagamentos')->insert([
            'nome' => 'Débito', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
