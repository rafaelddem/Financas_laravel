<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class Restore extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        echo Carbon::now()->format('d/m/Y H:i:s') . " - Nothing to restore..." . "\n";
    }
}