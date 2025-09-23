<?php

namespace Database\Seeders;

use App\Enums\Relevance;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->insert([
            [
                'id' => 1,
                'name' => 'Entradas diversas',
                'relevance' => Relevance::Banal->value,
            ],
            [
                'id' => 2,
                'name' => 'Saídas diversas',
                'relevance' => Relevance::Banal->value,
            ],
            [
                'id' => 3,
                'name' => 'Movimentação entre carteiras',
                'relevance' => Relevance::Banal->value,
            ],
        ]);
    }
}
