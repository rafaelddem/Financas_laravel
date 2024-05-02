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
            'id' => 1,
            'name' => 'Sistema',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // id: 2
        DB::table('owners')->insert([
            'id' => 2,
            'name' => 'Rafael',
            'active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
