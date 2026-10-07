<?php

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Support\Facades\Route;

test('GET /animaux/nouveau affiche un formulaire qui envoie en POST vers /animaux', function (): void {
    Shelter::factory()->create(['name' => 'Les Quatre Pattes']);

    expect(Route::has('animals.create'))->toBeTrue('La route animals.create n\'existe pas.');
    expect(Route::has('animals.store'))->toBeTrue('La route animals.store n\'existe pas.');

    $this->get('/animaux/nouveau')
        ->assertOk()
        ->assertSee('action="'.route('animals.store').'"', false)
        ->assertSee('method="POST"', false)
        ->assertSee('name="_token"', false)
        ->assertSee('name="name"', false)
        ->assertSee('name="species"', false)
        ->assertSee('name="shelter_id"', false)
        ->assertSee('name="birth_date"', false)
        ->assertSee('name="description"', false)
        ->assertSee('Les Quatre Pattes');
})->group('ajouter');

test('POST /animaux enregistre l\'animal et redirige vers sa fiche avec un message', function (): void {
    $shelter = Shelter::factory()->create();

    $response = $this->post('/animaux', validAnimal($shelter));

    $this->assertDatabaseCount('animals', 1);
    $this->assertDatabaseHas('animals', [
        'name' => 'Rex',
        'species' => 'chien',
        'shelter_id' => $shelter->id,
        'description' => 'Un berger croisé qui rapporte tout ce qu\'on lance.',
    ]);

    $animal = Animal::first();
    expect($animal->birth_date->toDateString())->toBe('2021-03-14');
    expect($animal->adopted_at)->toBeNull();

    $response
        ->assertRedirect(route('animals.show', $animal->id))
        ->assertSessionHas('status', 'L\'animal a été ajouté.');
})->group('ajouter');

test('le message de confirmation s\'affiche sur la fiche après l\'ajout', function (): void {
    $shelter = Shelter::factory()->create();

    $this->followingRedirects()
        ->post('/animaux', validAnimal($shelter))
        ->assertOk()
        ->assertSee('L\'animal a été ajouté.');
})->group('ajouter');
