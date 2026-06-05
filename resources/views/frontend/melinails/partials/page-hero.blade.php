<section class="relative overflow-hidden bg-stone-950 text-white">
    <img src="{{ $image ?? 'https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1800&q=82' }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-45">
    <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/80 to-stone-950/20"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8">
        <div class="max-w-3xl">
            @isset($eyebrow)
                <div class="text-xs font-bold uppercase tracking-[0.22em] text-rose-100">{{ $eyebrow }}</div>
            @endisset
            <h1 class="mt-4 text-4xl font-bold leading-tight sm:text-6xl">{{ $title }}</h1>
            @isset($text)
                <p class="mt-5 max-w-2xl text-lg leading-8 text-stone-200">{{ $text }}</p>
            @endisset
        </div>
    </div>
</section>
