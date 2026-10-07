<?php

use App\Models\Animal;
use App\Models\Shelter;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

test('la route animals.index répond à GET /animaux', function (): void {
    expect(Route::has('animals.index'))->toBeTrue('La route animals.index n\'existe pas.');
    expect(route('animals.index', absolute: false))->toBe('/animaux');

    $this->get('/animaux')->assertOk();
})->group('liste');

test('la liste affiche les animaux à adopter, triés par nom', function (): void {
    $shelter = Shelter::factory()->create();
    Animal::factory()->for($shelter)->create(['name' => 'Rex']);
    Animal::factory()->for($shelter)->create(['name' => 'Biscotte']);
    Animal::factory()->for($shelter)->create(['name' => 'Minette']);

    $this->get('/animaux')->assertOk()->assertSeeInOrder(['Biscotte', 'Minette', 'Rex']);
})->group('liste');

test('la liste ne montre pas les animaux adoptés', function (): void {
    $shelter = Shelter::factory()->create();
    Animal::factory()->for($shelter)->create(['name' => 'Rex']);
    Animal::factory()->for($shelter)->adopted()->create(['name' => 'Oscar']);

    $this->get('/animaux')->assertOk()->assertSee('Rex')->assertDontSee('Oscar');
})->group('liste');

test('une liste vide affiche « Aucun animal »', function (): void {
    $this->get('/animaux')->assertOk()->assertSee('Aucun animal');
})->group('liste');

test('la fiche affiche le nom, l\'espèce, le refuge et les traits de caractère', function (): void {
    $shelter = Shelter::factory()->create(['name' => 'Les Quatre Pattes']);
    $animal = Animal::factory()->for($shelter)->create(['name' => 'Rex', 'species' => 'chien']);
    $animal->tags()->attach(Tag::factory()->create(['name' => 'Joueur']));

    expect(Route::has('animals.show'))->toBeTrue('La route animals.show n\'existe pas.');

    $this->get("/animaux/{$animal->id}")
        ->assertOk()
        ->assertSee('Rex')
        ->assertSee('Chien')
        ->assertSee('Les Quatre Pattes')
        ->assertSee('Joueur');
})->group('liste');

test('la fiche d\'un animal adopté affiche « Adopté »', function (): void {
    $animal = Animal::factory()->adopted()->create();

    $this->get("/animaux/{$animal->id}")->assertOk()->assertSee('Adopté');
})->group('liste');

test('la fiche d\'un animal inconnu répond 404', function (): void {
    $this->get('/animaux/999')->assertNotFound();
})->group('liste');
