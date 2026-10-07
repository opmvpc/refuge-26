<?php

use App\Models\Animal;
use Illuminate\Support\Facades\Route;

test('PATCH /animaux/{id}/adoption enregistre la date d\'adoption et redirige vers la fiche', function (): void {
    $animal = Animal::factory()->create(['name' => 'Rex']);

    expect(Route::has('animals.adopt'))->toBeTrue('La route animals.adopt n\'existe pas.');

    // Comme le navigateur : un POST qui porte le champ caché _method=PATCH.
    $this->post("/animaux/{$animal->id}/adoption", ['_method' => 'PATCH'])
        ->assertRedirect(route('animals.show', $animal->id))
        ->assertSessionHas('status', 'Rex a trouvé une famille.');

    expect($animal->fresh()->adopted_at)->not->toBeNull();
    expect($animal->fresh()->adopted_at->isToday())->toBeTrue();
})->group('adopter');

test('la fiche d\'un animal à adopter contient le formulaire d\'adoption, celle d\'un animal adopté ne le contient plus', function (): void {
    $rex = Animal::factory()->create();
    $oscar = Animal::factory()->adopted()->create();

    $this->get("/animaux/{$rex->id}")
        ->assertOk()
        ->assertSee('action="'.route('animals.adopt', $rex->id).'"', false)
        ->assertSee('value="PATCH"', false);

    $this->get("/animaux/{$oscar->id}")
        ->assertOk()
        ->assertDontSee('action="'.route('animals.adopt', $oscar->id).'"', false)
        ->assertSee('Adopté');
})->group('adopter');

test('un animal adopté disparaît de la liste', function (): void {
    $animal = Animal::factory()->create(['name' => 'Rex']);

    // Comme le navigateur : la redirection vers la fiche affiche (et consomme) le message « Rex a trouvé une famille. ».
    $this->followingRedirects()->patch("/animaux/{$animal->id}/adoption");

    $this->get('/animaux')->assertOk()->assertDontSee('Rex');
})->group('adopter');

test('adopter un animal déjà adopté ne change pas sa date d\'adoption', function (): void {
    $animal = Animal::factory()->adopted()->create(['adopted_at' => '2026-09-12']);

    $this->patch("/animaux/{$animal->id}/adoption");

    expect($animal->fresh()->adopted_at->toDateString())->toBe('2026-09-12');
})->group('adopter');

test('adopter un animal inconnu répond 404', function (): void {
    $this->patch('/animaux/999/adoption')->assertNotFound();
})->group('adopter');
