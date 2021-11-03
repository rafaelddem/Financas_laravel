<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('owners')->insert([
            'name' => 'Não definida', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('owners')->insert([
            'name' => 'Rafael', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 3
        DB::table('owners')->insert([
            'name' => 'Terezinha', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 4
        DB::table('owners')->insert([
            'name' => 'Márcio', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
