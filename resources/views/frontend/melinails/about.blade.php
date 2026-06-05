@extends('layouts.master')

@section('title', 'O nás | Meli Nails & Beauty')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'O nás',
    'title' => 'Krása, péče a klid v jemném evropském stylu.',
    'text' => 'Salon vytvořený pro ženy, které chtějí profesionální péči bez spěchu.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-10 lg:grid-cols-2 lg:items-center">
        <div>
            <h2 class="text-3xl font-bold text-stone-950">Péče má být krásná, profesionální a příjemně lidská.</h2>
            <p class="mt-5 text-base leading-8 text-stone-600">Meli Nails & Beauty spojuje nail care, beauty procedury a relax. Nový Laravel základ počítá s více pobočkami, týmy, rozdílnou dostupností služeb a cenami podle salonu.</p>
            <div class="mt-8 grid gap-4 sm:grid-cols-3">
                <div class="rounded-sm bg-white p-5 shadow-sm"><strong class="block text-2xl text-stone-950">2</strong><span class="text-sm text-stone-600">pobočky</span></div>
                <div class="rounded-sm bg-white p-5 shadow-sm"><strong class="block text-2xl text-stone-950">70+</strong><span class="text-sm text-stone-600">služeb</span></div>
                <div class="rounded-sm bg-white p-5 shadow-sm"><strong class="block text-2xl text-stone-950">15 min</strong><span class="text-sm text-stone-600">booking slot</span></div>
            </div>
        </div>
        <img src="https://images.unsplash.com/photo-1521590832167-7bcbfaa6381f?auto=format&fit=crop&w=1200&q=82" alt="Meli Nails salon" class="aspect-[4/3] w-full rounded-sm object-cover shadow-xl">
    </div>
</section>
@endsection
