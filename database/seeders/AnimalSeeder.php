<?php

namespace Database\Seeders;

use App\Models\Animal;
use App\Models\Shelter;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class AnimalSeeder extends Seeder
{
    /**
     * Les douze animaux des trois refuges : dix à adopter, deux déjà adoptés.
     * Les dates sont fixes, pour que les captures du cours restent vraies.
     */
    public function run(): void
    {
        $animals = [
            ['Rex', 'chien', '2021-03-14', 'Les Quatre Pattes', ['Joueur', 'Ok enfants', 'Sportif'], null, "Un berger croisé qui rapporte tout ce qu'on lance."],
            ['Minette', 'chat', '2019-07-02', 'Les Quatre Pattes', ['Calme', 'Câlin'], null, 'Une chatte tigrée qui dort au soleil et réclame les genoux.'],
            ['Biscotte', 'lapin', '2023-01-20', 'Les Quatre Pattes', ['Craintif'], null, 'Un lapin nain qui demande du temps avant de se laisser porter.'],
            ['Pompon', 'lapin', '2024-12-12', 'Les Quatre Pattes', ['Joueur'], null, 'Un bélier curieux qui explore tout.'],
            ['Oscar', 'chien', '2016-11-05', 'Le Clos des Mimosas', ['Calme', 'Ok chats', 'Ok enfants'], '2026-09-12', 'Un labrador senior, posé, qui aime les longues balades lentes.'],
            ['Pacha', 'chat', '2022-05-30', 'Le Clos des Mimosas', ['Craintif', 'Ok chiens'], null, "Un chat noir discret qui s'apprivoise avec des friandises."],
            ['Nala', 'chien', '2024-02-09', 'Le Clos des Mimosas', ['Joueur', 'Sportif', 'Ok chiens'], null, 'Une jeune border collie qui a besoin de courir chaque jour.'],
            ['Nougat', 'chien', '2023-09-28', 'Le Clos des Mimosas', ['Craintif', 'Câlin'], null, null],
            ['Caramel', 'lapin', '2022-08-17', 'La Ferme du Bonheur', ['Câlin', 'Calme'], null, 'Un lapin roux qui aime les caresses derrière les oreilles.'],
            ['Simba', 'chat', '2018-10-23', 'La Ferme du Bonheur', ['Câlin', 'Ok enfants'], null, "Un gros chat roux qui ronronne dès qu'on le regarde."],
            ['Mia', 'chat', '2025-04-01', 'La Ferme du Bonheur', ['Joueur', 'Ok chats'], '2026-10-01', 'Une chatonne vive qui joue avec tout ce qui bouge.'],
            ['Léon', 'chien', '2020-06-18', 'La Ferme du Bonheur', ['Calme', 'Ok chats', 'Ok chiens'], null, 'Un beagle tranquille, parfait premier chien.'],
        ];

        foreach ($animals as [$name, $species, $birthDate, $shelterName, $tagNames, $adoptedAt, $description]) {
            $animal = Animal::create([
                'shelter_id' => Shelter::where('name', $shelterName)->value('id'),
                'name' => $name,
                'species' => $species,
                'birth_date' => $birthDate,
                'description' => $description,
                'adopted_at' => $adoptedAt,
            ]);

            $animal->tags()->attach(Tag::whereIn('name', $tagNames)->pluck('id'));
        }
    }
}
