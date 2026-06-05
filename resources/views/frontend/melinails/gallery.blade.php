@extends('layouts.master')

@section('title', 'Galerie | Meli Nails & Beauty')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Galerie',
    'title' => 'Výsledky, atmosféra a beauty detaily.',
    'text' => 'Až budou fotky salonu hotové, tato místa půjdou nahradit lokálními médii.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach($galleryItems as $item)
            <figure class="group overflow-hidden rounded-sm bg-white shadow-sm">
                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}" class="aspect-[4/3] w-full object-cover transition duration-500 group-hover:scale-105">
                <figcaption class="p-5">
                    <div class="text-xs font-bold uppercase tracking-[0.18em] text-rose-700">{{ $item['category'] }}</div>
                    <h2 class="mt-2 text-xl font-bold text-stone-950">{{ $item['title'] }}</h2>
                </figcaption>
            </figure>
        @endforeach
    </div>
</section>
@endsection
