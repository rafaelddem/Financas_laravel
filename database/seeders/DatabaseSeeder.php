<?php

namespace Database\Seeders;

use Database\Seeders\Testes\MovementSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeed::class,
            OwnerSeeder::class,
            WalletSeeder::class,
            PaymentMethodSeeder::class,
            CategorySeeder::class,
            TransactionBaseSeeder::class,
        ]);
    }
}
