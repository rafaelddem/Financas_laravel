<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoMovimentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('tipo_movimentos')->insert([
            'nome' => 'Entrada', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('tipo_movimentos')->insert([
            'nome' => 'Saída', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
