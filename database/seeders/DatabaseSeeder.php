<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Aqui você chama todos os seeders que quiser
        $this->call([
            QuestionSeeder::class,
            // CategorySeeder::class,
            // UserSeeder::class,
            // AlternativesSeeder::class,
        ]);
    }
}
