<?php

use App\Models\Shelter;
use Illuminate\Support\Facades\File;

test('les traductions françaises sont installées', function (): void {
    expect(File::exists(lang_path('fr/validation.php')))
        ->toBeTrue('Lancez composer require laravel-lang/common --dev, puis php artisan lang:add fr, et committez lang/fr/.');
    expect(config('app.locale'))->toBe('fr');
})->group('valider');

test('un formulaire vide est refusé : erreurs sur name, species et shelter_id, rien en base', function (): void {
    $this->from('/animaux/nouveau')
        ->post('/animaux', [])
        ->assertRedirect('/animaux/nouveau')
        ->assertSessionHasErrors(['name', 'species', 'shelter_id']);

    $this->assertDatabaseCount('animals', 0);
})->group('valider');

test('un nom de plus de 50 caractères est refusé', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['name' => str_repeat('a', 51)]))
        ->assertSessionHasErrors(['name']);

    $this->assertDatabaseCount('animals', 0);
})->group('valider');

test('une espèce inconnue est refusée', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['species' => 'dragon']))
        ->assertSessionHasErrors(['species']);

    $this->assertDatabaseCount('animals', 0);
})->group('valider');

test('un refuge qui n\'existe pas est refusé', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['shelter_id' => 999]))
        ->assertSessionHasErrors(['shelter_id']);

    $this->assertDatabaseCount('animals', 0);
})->group('valider');

test('une date de naissance dans le futur est refusée', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['birth_date' => today()->addDay()->toDateString()]))
        ->assertSessionHasErrors(['birth_date']);

    $this->assertDatabaseCount('animals', 0);
})->group('valider');

test('la date de naissance et la description sont facultatives', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['birth_date' => null, 'description' => null]))
        ->assertSessionHasNoErrors();

    $this->assertDatabaseHas('animals', ['name' => 'Rex', 'birth_date' => null, 'description' => null]);
})->group('valider');

test('le formulaire réaffiché garde les valeurs tapées et montre le message en français', function (): void {
    $shelter = Shelter::factory()->create();

    $this->from('/animaux/nouveau')
        ->followingRedirects()
        ->post('/animaux', validAnimal($shelter, ['name' => 'Rex', 'species' => '']))
        ->assertOk()
        ->assertSee('value="Rex"', false)
        ->assertSee('obligatoire');
})->group('valider');
