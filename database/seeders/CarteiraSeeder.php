<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarteiraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id:   1  | pessoa: Não definida
        DB::table('carteiras')->insert([
            'nome' => 'Entradas', 
            'pessoa' => 1, 
            'ativo' => true, 
            'principal' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   2  | pessoa: Não definida
        DB::table('carteiras')->insert([
            'nome' => 'Saídas', 
            'pessoa' => 1, 
            'ativo' => true, 
            'principal' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   3  | pessoa: Rafael
        DB::table('carteiras')->insert([
            'nome' => 'Casa', 
            'pessoa' => 2, 
            'ativo' => true, 
            'principal' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   4  | pessoa: Rafael
        DB::table('carteiras')->insert([
            'nome' => 'NuBank', 
            'pessoa' => 2, 
            'ativo' => true, 
            'principal' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   5  | pessoa: Rafael
        DB::table('carteiras')->insert([
            'nome' => 'NuConta', 
            'pessoa' => 2, 
            'ativo' => true, 
            'principal' => false, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   6  | pessoa: Terezinha
        DB::table('carteiras')->insert([
            'nome' => 'Casa', 
            'pessoa' => 3, 
            'ativo' => true, 
            'principal' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id:   7  | pessoa: Márcio
        DB::table('carteiras')->insert([
            'nome' => 'Casa', 
            'pessoa' => 4, 
            'ativo' => true, 
            'principal' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
