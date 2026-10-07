<article {{ $attributes->class([
    'overflow-hidden rounded-xl border border-zinc-200 bg-white shadow-xs',
    'dark:border-zinc-800 dark:bg-zinc-900',
]) }}>
    @isset($image)
        {{ $image }}
    @endisset

    <div class="p-5">
        {{ $slot }}
    </div>
</article>
