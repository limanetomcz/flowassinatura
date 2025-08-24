<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * The order of seeding is important:
     * 1. Companies
     * 2. Users
     * 3. Documents
     * 4. Signatures
     */
    public function run(): void
    {
        $this->call([
            CompaniesSeeder::class,             
            UsersSeeder::class,                
            DocumentSignatureSeeder::class,     
        ]);
    }
}
