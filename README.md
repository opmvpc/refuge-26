# Les katas du refuge (refuge-26)

Les exercices du bloc Formulaires de 5XCOS, chapitre 17 du cours (« Le refuge :
exercices »), à faire après les chapitres 15 et 16. **Un groupe de tests = un
kata.** Trois refuges du Brabant wallon publient les animaux qu'ils proposent à
l'adoption : les bénévoles ajoutent un animal, corrigent sa fiche, cochent ses
traits de caractère, le marquent adopté, et retirent une fiche créée par erreur.

Les tests sont déjà écrits. **Ils sont l'énoncé.** Les énoncés détaillés et les
critères de mise en forme sont dans le chapitre 17 du cours.

Il faut **PHP 8.4 ou plus**, Composer, Node et npm.

## Ce que le dépôt contient, et ce que vous écrivez

| Déjà dans le dépôt | Ce que vous écrivez |
|---|---|
| les migrations `shelters`, `tags`, `animals`, `animal_tag` | les routes de `routes/web.php` (sauf la redirection de `/` et la route `styleguide`) |
| l'enum `App\Enums\Species` (`label()`, `image()`), les modèles `Shelter`, `Animal`, `Tag` avec leurs relations | `AnimalController` et `ShelterController` |
| les fabriques et les seeders (3 refuges, 8 traits, 12 animaux) | la Form Request `AnimalRequest` (ou les règles dans `validate()`) |
| Tailwind branché, le layout `<x-layouts.app>`, les composants `<x-button>`, `<x-badge>`, `<x-card>`, `<x-nav-link>`, la page `/composants` | les vues `animals/index`, `show`, `create`, `edit` et `shelters/show` |
| les composants de champ `<x-label>`, `<x-input>`, `<x-select>`, `<x-textarea>` : les mêmes que dans `friterie-26`, le dépôt des chapitres 15 et 16 ; ils lisent `$errors` eux-mêmes | |
| les pictogrammes `public/images/species/chien.svg`, `chat.svg`, `lapin.svg` | l'affichage du message flash dans le layout (kata 17.2) |
| les tests, la CI | `lang/fr/`, installé avec le paquet Laravel-Lang (kata 17.3) |

Le modèle des traits de caractère s'appelle `Tag`, pas `Trait` : `trait` est un
mot réservé de PHP (vous l'avez utilisé au bloc POO), `class Trait` ne compile
pas. Dans les textes affichés, on écrit « trait de caractère ».

Le menu du layout pointe vers `/animaux` et `/animaux/nouveau` en adresses
écrites en dur, pas avec `route('animals.index')` : tant que vous n'avez pas
écrit la route, `route()` ferait planter toutes les pages, `/composants` compris.

## La procédure, pas à pas

1. **Forkez** ce dépôt sur votre compte GitHub (bouton *Fork* en haut à droite).
2. **Clonez** votre fork :
   ```bash
   git clone https://github.com/VOTRE-COMPTE/refuge-26.git
   cd refuge-26
   ```
3. **Installez** les dépendances et préparez le projet :
   ```bash
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate --seed
   npm install
   ```
   `php artisan migrate --seed` vous propose de créer `database/database.sqlite` :
   répondez `yes`. Il y met les trois refuges, les huit traits et les douze
   animaux.
4. **Lancez le site** :
   ```bash
   composer run dev
   ```
   Avec Herd ou Laragon, le site répond sur `http://refuge-26.test` (laissez
   `composer run dev` tourner : il compile le CSS). Ouvrez
   `http://refuge-26.test/composants` : la page des composants s'affiche. Toutes
   les autres pages répondent 404 : c'est normal, les routes sont à écrire.
5. **Lancez le premier groupe** :
   ```bash
   php artisan test --group=liste
   ```
   Presque tout est rouge. C'est le point de départ.
6. **Travaillez un kata à la fois**, dans l'ordre du tableau ci-dessous.
7. **Committez et poussez** dès qu'un groupe est vert :
   ```bash
   git add .
   git commit -m "kata 17.1 vert"
   git push
   ```
8. **Regardez l'onglet Actions** de votre fork sur GitHub : huit jobs, une coche
   verte ou une croix rouge pour chacun.

Les tests tournent sur une base en mémoire : votre `database.sqlite` n'est pas
touchée. Ils n'ont pas besoin de `npm` : ils ne regardent pas le CSS
(`withoutVite()` dans `tests/Pest.php`).

## Les groupes, dans l'ordre

| Groupe | Kata | Durée | Ce que vous écrivez |
|---|---|---|---|
| `liste` | 17.1 La liste et la fiche | ~20 min | `animals.index` et `animals.show`, la grille de cartes, la fiche |
| `ajouter` | 17.2 Ajouter un animal | ~25 min | `animals.create` et `animals.store`, le formulaire, le message flash dans le layout |
| `valider` | 17.3 Valider le formulaire | ~25 min | Laravel-Lang, les règles, `@error` et `old()` sous chaque champ |
| `modifier` | 17.4 Modifier un animal | ~25 min | `animals.edit` et `animals.update`, le formulaire pré-rempli, `@method('PUT')` |
| `caracteres` | 17.5 Les traits de caractère | ~25 min | les cases `tags[]`, `@checked`, `sync()` |
| `adopter` | 17.6 Adopter | ~15 min | `animals.adopt`, le bouton en `@method('PATCH')` |
| `supprimer` | 17.7 Supprimer | ~15 min | `animals.destroy`, le bouton en `@method('DELETE')` avec `confirm()` |
| `bonus` | 17.8 Le refuge et le filtre | ~30 min | `shelters.show`, le filtre `/animaux?espece=chat` |

## Comment lire un test rouge

Ouvrez `tests/Feature/ListeTest.php`. Chaque test porte une phrase qui dit ce qui
est attendu, par exemple :

```php
test('la liste affiche les animaux à adopter, triés par nom', function (): void {
```

Quand il échoue, Pest affiche cette phrase, puis la raison, puis la ligne du test.
Quelques raisons que vous rencontrerez :

- `La route animals.index n'existe pas.` suivi de `Failed asserting that false is true.` :
  la route manque, ou elle n'a pas ce nom.
- `Route [animals.show] not defined.` : même cause, le test a besoin de la route
  pour construire l'adresse attendue.
- `Expected response status code [200] but received 404.` : la page n'existe pas
  à cette adresse.
- Un long bloc qui commence par `<!DOCTYPE html>` : la page existe, mais il lui
  manque un texte. C'est la page reçue ; lisez la fin du message, elle dit le
  texte cherché (`contains "Biscotte"`).
- `Session is missing expected key [errors].` : le formulaire a été accepté alors
  qu'il aurait dû être refusé. Une règle de validation manque.
- `Failed asserting that a row in the table [animals] matches the attributes`
  suivi de deux tableaux : la ligne attendue et les lignes trouvées en base.
  Comparez-les champ par champ.
- `Failed asserting that table [animal_tag] matches expected entries count of 2. Entries found: 0.` :
  les traits cochés ne sont pas enregistrés (`sync()` manque).
- `Lancez composer require laravel-lang/common --dev, puis php artisan lang:add fr, et committez lang/fr/.` :
  les traductions françaises ne sont pas installées.

Six tests sur 41 sont verts dès le départ : les cinq « … répond 404 » (une
adresse qui n'existe pas répond déjà 404) et « adopter un animal déjà adopté ne
change pas sa date d'adoption » (rien ne la change tant que la route manque). Ils
restent verts quand vous écrivez les routes, à condition que la fiche d'un animal
inconnu réponde toujours 404 et que l'adoption ne touche pas un animal déjà adopté.

Pour ne relancer qu'un seul test pendant que vous cherchez :

```bash
vendor/bin/pest --filter="triés par nom"
```

## Ce que les tests imposent

Les tests envoient des requêtes sur ces adresses, avec ces verbes, et vérifient
ces noms de routes. `{id}` est un entier (`whereNumber('id')`) ou un paramètre de
liaison `{animal}` : les deux passent. Déclarez `/animaux/nouveau` avant
`/animaux/{id}`, ou mettez `whereNumber` sur `{id}`.

| Verbe | Adresse | Nom | Méthode | Effet attendu |
|---|---|---|---|---|
| `GET` | `/animaux` | `animals.index` | `AnimalController@index` | les animaux **non adoptés**, triés par nom ; bonus : `?espece=chat` filtre, une valeur inconnue est ignorée |
| `GET` | `/animaux/nouveau` | `animals.create` | `create` | le formulaire d'ajout, avec le `<select>` des refuges et les cases des traits |
| `POST` | `/animaux` | `animals.store` | `store` | valide, crée, `sync` les traits, redirige vers la fiche, flash `status` = « L'animal a été ajouté. » |
| `GET` | `/animaux/{id}` | `animals.show` | `show` | nom, espèce (libellé), refuge, âge, traits, badge « Adopté » le cas échéant, boutons Modifier, Adopter (si non adopté), Supprimer ; 404 sinon |
| `GET` | `/animaux/{id}/modifier` | `animals.edit` | `edit` | le formulaire pré-rempli, traits pré-cochés, `@method('PUT')` |
| `PUT` | `/animaux/{id}` | `animals.update` | `update` | valide, met à jour, `sync`, redirige vers la fiche, flash « L'animal a été modifié. » |
| `PATCH` | `/animaux/{id}/adoption` | `animals.adopt` | `adopt` | pose `adopted_at` à aujourd'hui si nul (ne change rien sinon), redirige vers la fiche, flash « {nom} a trouvé une famille. » |
| `DELETE` | `/animaux/{id}` | `animals.destroy` | `destroy` | supprime, redirige vers la liste, flash « L'animal a été supprimé. » |
| `GET` | `/refuges/{id}` | `shelters.show` | `ShelterController@show` | bonus : le refuge, sa ville, ses animaux à adopter |

Les champs du formulaire, avec les mêmes noms à l'ajout et à la modification :

| Champ | Élément | Règles |
|---|---|---|
| `name` | `<input type="text">` | `required`, `string`, `max:50` |
| `species` | `<select>` sur `Species::cases()`, `value="{{ $species->value }}"` | `required`, `Rule::enum(Species::class)` |
| `shelter_id` | `<select>` sur les refuges | `required`, `exists:shelters,id` |
| `birth_date` | `<input type="date">` | `nullable`, `date`, `before_or_equal:today` |
| `description` | `<textarea>` | `nullable`, `string`, `max:1000` |
| `tags` | cases `name="tags[]"` `value="{{ $tag->id }}"` | `nullable`, `array` ; `tags.*` : `integer`, `exists:tags,id` |

Les noms des champs dans les messages : `name` → nom, `species` → espèce,
`shelter_id` → refuge, `birth_date` → date de naissance, `description` →
description, `tags` → traits de caractère.

Les textes que les tests cherchent dans les pages : les trois messages flash
ci-dessus et « {nom} a trouvé une famille. », « Adopté », « Aucun animal » (liste
vide), les libellés « Chien », « Chat », « Lapin ».

Le reste (le HTML de vos vues, les classes Tailwind, l'ordre des champs) est
libre : les critères de mise en forme sont dans le chapitre 17.

## Ce qui est dans le dossier

| Fichier | Rôle |
|---|---|
| `tests/Feature/ListeTest.php` … `BonusTest.php` | Les énoncés, un fichier par kata, chaque test marqué `->group('liste')` (etc.). **Ne les modifiez pas.** |
| `tests/Pest.php` | Configuration de Pest : la base en mémoire remise à neuf avant chaque test, `withoutVite()`, et la fonction `validAnimal()` qui fabrique un formulaire valide. |
| `.github/workflows/tests.yml` | La CI : un job par kata, huit résultats visibles dans l'onglet Actions. |
| `resources/views/components/` | Le layout et les quatre composants, documentés sur `/composants`. |
| `database/` | Les migrations, les fabriques et les seeders du refuge. |
