<?php

use App\Models\Animal;
use App\Models\Shelter;
use Illuminate\Support\Facades\Route;

test('GET /animaux/{id}/modifier affiche le formulaire pré-rempli, qui envoie en PUT', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create([
        'name' => 'Rex',
        'description' => 'Rapporte tout ce qu\'on lance.',
    ]);

    expect(Route::has('animals.edit'))->toBeTrue('La route animals.edit n\'existe pas.');
    expect(Route::has('animals.update'))->toBeTrue('La route animals.update n\'existe pas.');

    $this->get("/animaux/{$animal->id}/modifier")
        ->assertOk()
        ->assertSee('action="'.route('animals.update', $animal->id).'"', false)
        ->assertSee('name="_method"', false)
        ->assertSee('value="PUT"', false)
        ->assertSee('value="Rex"', false)
        ->assertSee('Rapporte tout ce qu\'on lance.');
})->group('modifier');

test('envoyer le formulaire de modification met l\'animal à jour et redirige vers sa fiche', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create(['name' => 'Rex']);

    // Comme le navigateur : un POST qui porte le champ caché _method=PUT.
    $response = $this->post("/animaux/{$animal->id}", validAnimal($shelter, [
        '_method' => 'PUT',
        'name' => 'Rexou',
        'species' => 'chien',
    ]));

    $response
        ->assertRedirect(route('animals.show', $animal->id))
        ->assertSessionHas('status', 'L\'animal a été modifié.');

    $this->assertDatabaseHas('animals', ['id' => $animal->id, 'name' => 'Rexou']);
    $this->assertDatabaseCount('animals', 1);
})->group('modifier');

test('la modification est validée comme l\'ajout', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create(['name' => 'Rex']);

    $this->from("/animaux/{$animal->id}/modifier")
        ->put("/animaux/{$animal->id}", validAnimal($shelter, ['name' => '']))
        ->assertRedirect("/animaux/{$animal->id}/modifier")
        ->assertSessionHasErrors(['name']);

    $this->assertDatabaseHas('animals', ['id' => $animal->id, 'name' => 'Rex']);
})->group('modifier');

test('modifier un animal inconnu répond 404', function (): void {
    $shelter = Shelter::factory()->create();

    $this->get('/animaux/999/modifier')->assertNotFound();
    $this->put('/animaux/999', validAnimal($shelter))->assertNotFound();
})->group('modifier');
