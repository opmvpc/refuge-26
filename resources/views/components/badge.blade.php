@props(['variant' => 'species'])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium whitespace-nowrap ring-1 ring-inset',
    'bg-brand-50 text-brand-700 ring-brand-600/20 dark:bg-brand-400/10 dark:text-brand-300 dark:ring-brand-400/30' => $variant === 'adopted',
    'bg-zinc-100 text-zinc-700 ring-zinc-500/20 dark:bg-zinc-400/10 dark:text-zinc-300 dark:ring-zinc-400/30' => $variant === 'species',
    'bg-white text-zinc-700 ring-zinc-300 dark:bg-zinc-900 dark:text-zinc-300 dark:ring-zinc-700' => $variant === 'tag',
])>
    {{ $slot }}
</span>
