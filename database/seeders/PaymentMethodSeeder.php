<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // id: 1
        DB::table('payment_methods')->insert([
            'name' => 'Dinheiro', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 2
        DB::table('payment_methods')->insert([
            'name' => 'Crédito', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);

        // id: 3
        DB::table('payment_methods')->insert([
            'name' => 'Débito', 
            'active' => true, 
            'created_at' => now(), 
            'updated_at' => now(), 
        ]);
    }
}
