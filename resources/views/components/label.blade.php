{{-- Le libellé d'un champ. Même classes que l'exercice 14.7. --}}
@props(['for'])

<label for="{{ $for }}" {{ $attributes->class(['block text-sm font-medium']) }}>{{ $slot }}</label>
