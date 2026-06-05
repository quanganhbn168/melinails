<div class="mx-auto max-w-3xl text-center">
    @isset($eyebrow)
        <div class="text-xs font-bold uppercase tracking-[0.22em] text-rose-700">{{ $eyebrow }}</div>
    @endisset
    <h2 class="mt-3 text-3xl font-bold text-stone-950 sm:text-4xl">{{ $title }}</h2>
    @isset($text)
        <p class="mt-4 text-base leading-7 text-stone-600">{{ $text }}</p>
    @endisset
</div>
