<?php

use App\Models\Animal;
use App\Models\Tag;
use Illuminate\Support\Facades\Route;

test('la fiche contient un formulaire de suppression qui envoie en DELETE', function (): void {
    $animal = Animal::factory()->create();

    expect(Route::has('animals.destroy'))->toBeTrue('La route animals.destroy n\'existe pas.');

    $this->get("/animaux/{$animal->id}")
        ->assertOk()
        ->assertSee('action="'.route('animals.destroy', $animal->id).'"', false)
        ->assertSee('value="DELETE"', false);
})->group('supprimer');

test('DELETE /animaux/{id} supprime l\'animal et redirige vers la liste', function (): void {
    $animal = Animal::factory()->create();

    // Comme le navigateur : un POST qui porte le champ caché _method=DELETE.
    $this->post("/animaux/{$animal->id}", ['_method' => 'DELETE'])
        ->assertRedirect(route('animals.index'))
        ->assertSessionHas('status', 'L\'animal a été supprimé.');

    $this->assertModelMissing($animal);
    $this->assertDatabaseCount('animals', 0);
})->group('supprimer');

test('supprimer un animal supprime aussi ses lignes dans animal_tag', function (): void {
    $animal = Animal::factory()->create();
    $animal->tags()->attach(Tag::factory()->count(2)->create());
    $this->assertDatabaseCount('animal_tag', 2);

    $this->delete("/animaux/{$animal->id}");

    $this->assertDatabaseCount('animal_tag', 0);
})->group('supprimer');

test('supprimer un animal inconnu répond 404', function (): void {
    $this->delete('/animaux/999')->assertNotFound();
})->group('supprimer');
