<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Rafael',
            'email' => 'rafaelddem@gmail.com',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        ]);

    }
}
