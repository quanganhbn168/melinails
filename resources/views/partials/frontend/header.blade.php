@php
    $meliNav = [
        ['label' => 'Služby', 'url' => route('meli.services')],
        ['label' => 'Ceník', 'url' => route('meli.prices')],
        ['label' => 'Galerie', 'url' => route('meli.gallery')],
        ['label' => 'Pobočky', 'url' => route('meli.branches')],
        ['label' => 'O nás', 'url' => route('meli.about')],
        ['label' => 'Kontakt', 'url' => route('meli.contact')],
    ];
@endphp

<header class="sticky top-0 z-50 border-b border-stone-200/80 bg-[#fff7f7]/95 backdrop-blur" x-data="{ open: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Meli Nails & Beauty">
            <img src="{{ asset('melinails/assets/logo.png') }}" alt="Meli Nails & Beauty" class="h-12 w-auto object-contain">
        </a>

        <nav class="hidden items-center gap-7 lg:flex" aria-label="Hlavní navigace">
            @foreach($meliNav as $item)
                <a href="{{ $item['url'] }}" class="text-sm font-semibold text-stone-700 transition hover:text-rose-700">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        <div class="hidden items-center gap-3 lg:flex">
            <a href="tel:+420777768681" class="text-sm font-semibold text-stone-700 hover:text-rose-700">+420 777 768 681</a>
            <a href="{{ route('meli.booking') }}" class="inline-flex items-center justify-center rounded-sm bg-stone-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-800">
                Rezervace
            </a>
        </div>

        <button type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-sm border border-stone-300 text-stone-900 lg:hidden" @click="open = !open" :aria-expanded="open.toString()" aria-controls="mobile-menu">
            <span class="sr-only">Otevřít menu</span>
            <i class="fa-solid" :class="open ? 'fa-xmark' : 'fa-bars'"></i>
        </button>
    </div>

    <div id="mobile-menu" class="border-t border-stone-200 bg-[#fff7f7] px-4 py-4 lg:hidden" x-show="open" x-transition>
        <nav class="flex flex-col gap-1" aria-label="Mobilní navigace">
            @foreach($meliNav as $item)
                <a href="{{ $item['url'] }}" class="rounded-sm px-3 py-3 text-sm font-semibold text-stone-800 hover:bg-white">
                    {{ $item['label'] }}
                </a>
            @endforeach
            <a href="{{ route('meli.booking') }}" class="mt-2 inline-flex items-center justify-center rounded-sm bg-stone-950 px-5 py-3 text-sm font-semibold text-white">
                Rezervovat termín
            </a>
        </nav>
    </div>
</header>
