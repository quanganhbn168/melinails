@extends('layouts.master')

@section('title', 'Služby | Meli Nails & Beauty')
@section('meta_description', 'Přehled služeb Meli Nails & Beauty: manikúra, pedikúra, řasy, obočí, kosmetika, masáže, depilace a poukazy.')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Služby',
    'title' => 'Krása, péče a relax v jednom prostoru.',
    'text' => 'Kompletní nabídka služeb připravená pro dvě pobočky a budoucí online booking.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[280px_1fr]">
        <aside class="lg:sticky lg:top-28 lg:self-start">
            <div class="rounded-sm border border-stone-200 bg-white p-5">
                <h2 class="text-sm font-bold uppercase tracking-[0.2em] text-stone-950">Kategorie</h2>
                <div class="mt-4 space-y-2">
                    @foreach($categories as $category)
                        <a href="{{ route('meli.service.category', $category->slug_value) }}" class="block rounded-sm px-3 py-2 text-sm font-semibold text-stone-700 hover:bg-rose-50 hover:text-rose-800">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </aside>
        <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-3">
            @foreach($services as $service)
                @include('frontend.melinails.partials.service-card', ['service' => $service])
            @endforeach
        </div>
    </div>
</section>
@endsection
