<?php

namespace Database\Seeders;

use App\Enums\Role;
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
            'role' => Role::Admin->value,
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Cliente',
            'email' => 'cliente@mail.com',
            'role' => Role::Client->value,
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        ]);

        User::create([
            'name' => 'Convidado',
            'email' => 'convidado@mail.com',
            'role' => Role::Guest->value,
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        ]);
    }
}
