<?php

use App\Models\Animal;
use App\Models\Shelter;
use App\Models\Tag;

test('le formulaire d\'ajout propose une case par trait de caractère', function (): void {
    Shelter::factory()->create();
    Tag::factory()->create(['name' => 'Joueur']);

    $this->get('/animaux/nouveau')
        ->assertOk()
        ->assertSee('name="tags[]"', false)
        ->assertSee('Joueur');
})->group('caracteres');

test('à l\'ajout, les traits cochés sont enregistrés dans animal_tag', function (): void {
    $shelter = Shelter::factory()->create();
    [$joueur, $calme, $craintif] = Tag::factory()->count(3)->create();

    $this->post('/animaux', validAnimal($shelter, ['tags' => [$joueur->id, $calme->id]]));

    $animal = Animal::first();
    $this->assertDatabaseCount('animal_tag', 2);
    $this->assertDatabaseHas('animal_tag', ['animal_id' => $animal->id, 'tag_id' => $joueur->id]);
    $this->assertDatabaseHas('animal_tag', ['animal_id' => $animal->id, 'tag_id' => $calme->id]);
    $this->assertDatabaseMissing('animal_tag', ['animal_id' => $animal->id, 'tag_id' => $craintif->id]);
})->group('caracteres');

test('le formulaire de modification pré-coche les traits de l\'animal', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create();
    [$joueur, $calme] = Tag::factory()->count(2)->create();
    $animal->tags()->attach($joueur);

    $html = $this->get("/animaux/{$animal->id}/modifier")->assertOk()->getContent();

    expect($html)->toMatch('/value="'.$joueur->id.'"[^>]*checked/');
    expect($html)->not->toMatch('/value="'.$calme->id.'"[^>]*checked/');
})->group('caracteres');

test('à la modification, la liste des traits est remplacée', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create();
    [$joueur, $calme, $craintif] = Tag::factory()->count(3)->create();
    $animal->tags()->attach([$joueur->id, $calme->id]);

    $this->put("/animaux/{$animal->id}", validAnimal($shelter, ['tags' => [$craintif->id]]));

    $this->assertDatabaseCount('animal_tag', 1);
    $this->assertDatabaseHas('animal_tag', ['animal_id' => $animal->id, 'tag_id' => $craintif->id]);
})->group('caracteres');

test('décocher toutes les cases retire tous les traits', function (): void {
    $shelter = Shelter::factory()->create();
    $animal = Animal::factory()->for($shelter)->create();
    $animal->tags()->attach(Tag::factory()->count(2)->create());

    // Aucune case cochée : le champ tags n'est pas envoyé du tout.
    $this->put("/animaux/{$animal->id}", validAnimal($shelter));

    $this->assertDatabaseCount('animal_tag', 0);
})->group('caracteres');

test('un trait qui n\'existe pas est refusé', function (): void {
    $shelter = Shelter::factory()->create();

    $this->post('/animaux', validAnimal($shelter, ['tags' => [999]]))
        ->assertSessionHasErrors(['tags.0']);

    $this->assertDatabaseCount('animals', 0);
})->group('caracteres');
