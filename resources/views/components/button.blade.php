@props(['variant' => 'primary', 'href' => null])

@php
    $classes = [
        'inline-flex items-center justify-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold shadow-xs transition-colors',
        'focus-visible:outline-2 focus-visible:outline-offset-2',
        'disabled:cursor-not-allowed disabled:opacity-50',
        'bg-brand-600 text-white hover:bg-brand-700 focus-visible:outline-brand-600 disabled:hover:bg-brand-600' => $variant === 'primary',
        'bg-white text-zinc-900 ring-1 ring-zinc-300 ring-inset hover:bg-zinc-50 focus-visible:outline-zinc-500 disabled:hover:bg-white dark:bg-zinc-800 dark:text-zinc-100 dark:ring-zinc-700 dark:hover:bg-zinc-700' => $variant === 'secondary',
        'bg-red-600 text-white hover:bg-red-700 focus-visible:outline-red-600 disabled:hover:bg-red-600' => $variant === 'danger',
    ];
@endphp

{{-- Avec href, le bouton est un lien qui en a l'apparence : <x-button href="/animaux">. --}}
@if ($href !== null)
    <a href="{{ $href }}" {{ $attributes->class($classes) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['type' => 'button'])->class($classes) }}>
        {{ $slot }}
    </button>
@endif
