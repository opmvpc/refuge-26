<?php

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Support\Facades\Route;

test('GET /refuges/{id} affiche le refuge, sa ville et ses animaux à adopter', function (): void {
    $shelter = Shelter::factory()->create(['name' => 'Les Quatre Pattes', 'city' => 'Wavre']);
    $other = Shelter::factory()->create();
    Animal::factory()->for($shelter)->create(['name' => 'Rex']);
    Animal::factory()->for($shelter)->adopted()->create(['name' => 'Oscar']);
    Animal::factory()->for($other)->create(['name' => 'Pacha']);

    expect(Route::has('shelters.show'))->toBeTrue('La route shelters.show n\'existe pas.');

    $this->get("/refuges/{$shelter->id}")
        ->assertOk()
        ->assertSee('Les Quatre Pattes')
        ->assertSee('Wavre')
        ->assertSee('Rex')
        ->assertDontSee('Oscar')
        ->assertDontSee('Pacha');
})->group('bonus');

test('GET /refuges/999 répond 404', function (): void {
    $this->get('/refuges/999')->assertNotFound();
})->group('bonus');

test('/animaux?espece=chat ne garde que les chats', function (): void {
    Animal::factory()->create(['name' => 'Rex', 'species' => 'chien']);
    Animal::factory()->create(['name' => 'Minette', 'species' => 'chat']);

    $this->get('/animaux?espece=chat')->assertOk()->assertSee('Minette')->assertDontSee('Rex');
})->group('bonus');

test('une espèce inconnue dans le filtre est ignorée', function (): void {
    Animal::factory()->create(['name' => 'Rex', 'species' => 'chien']);
    Animal::factory()->create(['name' => 'Minette', 'species' => 'chat']);

    $this->get('/animaux?espece=dragon')->assertOk()->assertSee('Minette')->assertSee('Rex');
})->group('bonus');
