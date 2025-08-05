<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => 'Usuário ' . $i,
                'email' => 'usuario' . $i . '@teste.com',
                'email_verified_at' => now(),
                'password' => Hash::make('senha123'),
                'remember_token' => Str::random(10),
            ]);
        }
    }
}
