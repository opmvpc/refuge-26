<x-layouts.app title="Composants">
    <div class="mb-8">
        <h1 class="text-3xl font-semibold tracking-tight">Les composants du site</h1>
        <p class="mt-2 text-zinc-600 dark:text-zinc-400">Chaque élément de l'interface, dans toutes ses variantes, sur une seule page.</p>
    </div>

    <div class="space-y-10">
        <section>
            <h2 class="text-xl font-semibold tracking-tight">Boutons</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"><code>&lt;x-button&gt;</code>, prop <code>variant</code> : <code>primary</code> (par défaut), <code>secondary</code>, <code>danger</code>. Dans un formulaire : <code>type="submit"</code>. Avec <code>href</code>, le composant écrit un lien <code>&lt;a&gt;</code> qui a l'apparence d'un bouton.</p>
            <p class="mt-4 flex flex-wrap gap-3">
                <x-button>Enregistrer</x-button>
                <x-button variant="secondary">Annuler</x-button>
                <x-button variant="danger">Supprimer</x-button>
                <x-button disabled>Indisponible</x-button>
                <x-button href="/composants" variant="secondary">Un lien en forme de bouton</x-button>
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold tracking-tight">Badges</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"><code>&lt;x-badge&gt;</code>, prop <code>variant</code> : <code>adopted</code> (un animal adopté), <code>species</code> (l'espèce, par défaut), <code>tag</code> (un trait de caractère).</p>
            <p class="mt-4 flex flex-wrap gap-3">
                <x-badge variant="adopted">Adopté</x-badge>
                <x-badge variant="species">Chien</x-badge>
                <x-badge variant="tag">Joueur</x-badge>
                <x-badge variant="tag">Ok enfants</x-badge>
            </p>
        </section>

        <section>
            <h2 class="text-xl font-semibold tracking-tight">Cartes</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"><code>&lt;x-card&gt;</code>, avec un slot facultatif <code>&lt;x-slot:image&gt;</code> au-dessus du contenu.</p>
            <div class="mt-4 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <x-card>
                    <h3 class="font-semibold">Titre de la carte</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">Le contenu de la carte : un texte court, une liste ou une image.</p>
                </x-card>

                <x-card>
                    <x-slot:image>
                        <img src="{{ asset('images/species/chat.svg') }}" alt="" width="200" height="200" class="aspect-4/3 w-full bg-brand-50 object-contain dark:bg-brand-100">
                    </x-slot:image>

                    <h3 class="font-semibold">Une carte avec image</h3>
                    <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">L'image passe dans le slot <code>image</code>, le texte dans le contenu.</p>
                </x-card>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold tracking-tight">Champs de formulaire</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"><code>&lt;x-label for="…"&gt;</code>, <code>&lt;x-input name="…"&gt;</code> (prop <code>type</code>, <code>text</code> par défaut), <code>&lt;x-select name="…"&gt;</code> (les <code>&lt;option&gt;</code> dans le slot), <code>&lt;x-textarea name="…"&gt;</code> (le contenu dans le slot). L'<code>id</code> vaut le <code>name</code> si on n'en donne pas. Chaque champ lit <code>$errors</code> lui-même : si le serveur a refusé la valeur, la bordure passe en rouge et le champ reçoit <code>aria-invalid="true"</code> et <code>aria-describedby="{name}-error"</code>. Le message s'écrit dans la vue, sous le champ : <code>@@error('name') &lt;p id="name-error" …&gt;@{{ $message }}&lt;/p&gt; @@enderror</code>.</p>
            <div class="mt-4 grid max-w-xl gap-6">
                <div>
                    <x-label for="demo-nom">Nom</x-label>
                    <x-input name="demo-nom" value="Biscotte" />
                </div>

                <div>
                    <x-label for="demo-espece">Espèce</x-label>
                    <x-select name="demo-espece">
                        <option value="">Choisir une espèce</option>
                        <option value="chien">Chien</option>
                        <option value="chat" selected>Chat</option>
                        <option value="lapin">Lapin</option>
                    </x-select>
                </div>

                <div>
                    <x-label for="demo-description">Description</x-label>
                    <x-textarea name="demo-description">Une chatte calme qui aime les genoux.</x-textarea>
                </div>

                {{-- Pour montrer l'état d'erreur sans envoyer de formulaire, on ajoute un message au sac $errors.
                     $errors->add() seul ne suffit pas : sans erreur en session, le sac « default » n'existe pas encore
                     et add() écrirait dans un sac jeté aussitôt. On le récupère, on ajoute, on le range. --}}
                @php
                    $errors->put('default', $errors->getBag('default')->add('demo', 'Le champ démo est obligatoire.'));
                @endphp
                <div>
                    <x-label for="demo">Un champ refusé par le serveur</x-label>
                    <x-input name="demo" />
                    @error('demo')
                        <p id="demo-error" class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>

        <section>
            <h2 class="text-xl font-semibold tracking-tight">Pictogrammes des espèces</h2>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400"><code>public/images/species/chien.svg</code>, <code>chat.svg</code>, <code>lapin.svg</code> : le chemin est donné par <code>$animal-&gt;species-&gt;image()</code>, à passer à <code>asset()</code>.</p>
            <p class="mt-4 flex flex-wrap gap-4">
                <img src="{{ asset('images/species/chien.svg') }}" alt="Chien" width="96" height="96" class="rounded-xl bg-brand-50 p-2 dark:bg-brand-100">
                <img src="{{ asset('images/species/chat.svg') }}" alt="Chat" width="96" height="96" class="rounded-xl bg-brand-50 p-2 dark:bg-brand-100">
                <img src="{{ asset('images/species/lapin.svg') }}" alt="Lapin" width="96" height="96" class="rounded-xl bg-brand-50 p-2 dark:bg-brand-100">
            </p>
        </section>
    </div>
</x-layouts.app>
