<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovementTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('movement_types')->insert([
            'name' => 'Entrada', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('movement_types')->insert([
            'name' => 'Saída', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
