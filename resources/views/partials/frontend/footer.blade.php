@php
    $footerBranches = $meliBranches ?? \App\Models\Branch::query()->where('status', true)->orderBy('id')->get();
@endphp

<footer class="bg-stone-950 text-stone-200">
    <div class="mx-auto grid max-w-7xl gap-10 px-4 py-14 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <div class="lg:col-span-2">
            <img src="{{ asset('melinails/assets/logo.png') }}" alt="Meli Nails & Beauty" class="h-14 w-auto brightness-0 invert">
            <p class="mt-6 max-w-xl text-sm leading-7 text-stone-400">
                Beauty salon pro manikúru, pedikúru, kosmetiku, řasy, obočí, depilaci a masáže. Dvě pobočky, jeden klidný standard péče.
            </p>
            <div class="mt-6 flex flex-wrap gap-3">
                <a href="{{ route('meli.booking') }}" class="rounded-sm bg-white px-5 py-2.5 text-sm font-semibold text-stone-950 transition hover:bg-rose-100">Rezervace</a>
                <a href="{{ route('meli.contact') }}" class="rounded-sm border border-white/20 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-white/10">Kontakt</a>
            </div>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.18em] text-white">Navigace</h2>
            <ul class="mt-5 space-y-3 text-sm text-stone-400">
                <li><a class="hover:text-white" href="{{ route('meli.services') }}">Služby</a></li>
                <li><a class="hover:text-white" href="{{ route('meli.prices') }}">Ceník</a></li>
                <li><a class="hover:text-white" href="{{ route('meli.gallery') }}">Galerie</a></li>
                <li><a class="hover:text-white" href="{{ route('meli.branches') }}">Pobočky</a></li>
                <li><a class="hover:text-white" href="{{ route('meli.about') }}">O nás</a></li>
            </ul>
        </div>

        <div>
            <h2 class="text-sm font-semibold uppercase tracking-[0.18em] text-white">Pobočky</h2>
            <div class="mt-5 space-y-5 text-sm text-stone-400">
                @foreach($footerBranches as $branch)
                    <div>
                        <strong class="block text-white">{{ $branch->name }}</strong>
                        <span class="mt-1 block">{{ $branch->address }}</span>
                        <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}" class="mt-1 inline-block hover:text-white">{{ $branch->phone }}</a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="border-t border-white/10 px-4 py-5 text-center text-xs text-stone-500">
        © {{ now()->year }} Meli Nails & Beauty. Uherské Hradiště • Zlín • info@melinails.cz
    </div>
</footer>
