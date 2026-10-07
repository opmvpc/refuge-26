<?php

use App\Models\Shelter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
| Chaque test de tests/Feature démarre sur une base SQLite en mémoire, migrée
| à neuf (RefreshDatabase) : votre database/database.sqlite n'est jamais touchée.
| withoutVite() : les tests ne regardent pas le CSS, ils tournent sans npm run build.
*/
pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(fn () => $this->withoutVite())
    ->in('Feature');

/** Un formulaire d'animal complet et valide. Les tests changent un champ à la fois. */
function validAnimal(Shelter $shelter, array $overrides = []): array
{
    return array_merge([
        'name' => 'Rex',
        'species' => 'chien',
        'shelter_id' => $shelter->id,
        'birth_date' => '2021-03-14',
        'description' => 'Un berger croisé qui rapporte tout ce qu\'on lance.',
    ], $overrides);
}

/**
 * Les cases à cocher name="tags[]" de la page : [id du trait => cochée ou non].
 * Le HTML est lu comme le lit un navigateur : l'ordre des attributs et les classes sont libres.
 */
function tagCheckboxes(string $html): array
{
    $document = Dom\HTMLDocument::createFromString($html, LIBXML_NOERROR);
    $checkboxes = [];

    foreach ($document->querySelectorAll('input[type="checkbox"][name="tags[]"]') as $input) {
        $checkboxes[(int) $input->getAttribute('value')] = $input->hasAttribute('checked');
    }

    return $checkboxes;
}
