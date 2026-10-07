<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // L'utilisateur de test du squelette : le cours n'a pas d'authentification, il ne sert pas.
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call([
            ShelterSeeder::class,
            TagSeeder::class,
            AnimalSeeder::class,
        ]);
    }
}
