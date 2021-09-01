<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PessoaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('pessoas')->insert([
            'nome' => 'Não definida', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('pessoas')->insert([
            'nome' => 'Rafael', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 3
        DB::table('pessoas')->insert([
            'nome' => 'Terezinha', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 4
        DB::table('pessoas')->insert([
            'nome' => 'Márcio', 
            'ativo' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
