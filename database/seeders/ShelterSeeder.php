<?php

namespace Database\Seeders;

use App\Models\Shelter;
use Illuminate\Database\Seeder;

class ShelterSeeder extends Seeder
{
    /**
     * Les trois refuges du Brabant wallon (noms inventés).
     */
    public function run(): void
    {
        Shelter::create(['name' => 'Les Quatre Pattes', 'city' => 'Wavre']);
        Shelter::create(['name' => 'Le Clos des Mimosas', 'city' => 'Rixensart']);
        Shelter::create(['name' => 'La Ferme du Bonheur', 'city' => 'Ottignies']);
    }
}
