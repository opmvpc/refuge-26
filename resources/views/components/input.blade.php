{{--
    Un champ de saisie. Le composant lit lui-même $errors : si le serveur a refusé la valeur,
    la bordure passe en rouge et le champ annonce son message (aria-invalid, aria-describedby).
    La vue garde @error('name') … @enderror sous le champ, avec id="name-error".
    Classes de l'exercice 14.7 du chapitre Tailwind.
--}}
@props(['name', 'type' => 'text'])

@php
    $invalid = $errors->has($name);
@endphp

<input
    type="{{ $type }}"
    name="{{ $name }}"
    @if ($invalid) aria-invalid="true" aria-describedby="{{ $name }}-error" @endif
    {{ $attributes->merge(['id' => $name])->class([
        'mt-2 block w-full rounded-lg border bg-white px-3 py-2 shadow-xs focus:ring-3 focus:outline-hidden dark:bg-zinc-950',
        'border-zinc-300 placeholder:text-zinc-400 focus:border-brand-600 focus:ring-brand-600/20 dark:border-zinc-700' => ! $invalid,
        'border-red-500 text-red-900 focus:border-red-600 focus:ring-red-600/20 dark:border-red-400 dark:text-red-100' => $invalid,
    ]) }}
>
