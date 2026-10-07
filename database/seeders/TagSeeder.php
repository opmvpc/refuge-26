<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Les huit traits de caractère, dans l'ordre alphabétique.
     */
    public function run(): void
    {
        foreach (['Calme', 'Câlin', 'Craintif', 'Joueur', 'Ok chats', 'Ok chiens', 'Ok enfants', 'Sportif'] as $name) {
            Tag::create(['name' => $name]);
        }
    }
}
