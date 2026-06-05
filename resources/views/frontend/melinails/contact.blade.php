@extends('layouts.master')

@section('title', 'Kontakt | Meli Nails & Beauty')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Kontakt',
    'title' => 'Jsme tu pro vaše dotazy i rezervace.',
    'text' => 'Napište nám, zavolejte nebo si rovnou vyberte pobočku a termín.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[0.9fr_1.1fr]">
        <div class="space-y-5">
            @foreach($meliBranches as $branch)
                <article class="rounded-sm border border-stone-200 bg-white p-6">
                    <h2 class="text-2xl font-bold text-stone-950">{{ $branch->name }}</h2>
                    <p class="mt-2 text-stone-600">{{ $branch->address }}</p>
                    <div class="mt-4 text-sm text-stone-700">
                        <a class="block text-rose-700" href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}">{{ $branch->phone }}</a>
                        <a class="block text-rose-700" href="mailto:{{ $branch->email }}">{{ $branch->email }}</a>
                    </div>
                </article>
            @endforeach
        </div>

        <form action="{{ route('contact.store') }}" method="POST" class="rounded-sm border border-stone-200 bg-white p-6 shadow-sm">
            @csrf
            <h2 class="text-2xl font-bold text-stone-950">Máte dotaz?</h2>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                <input name="name" type="text" placeholder="Jméno" class="rounded-sm border-stone-300" required>
                <input name="phone" type="tel" placeholder="Telefon" class="rounded-sm border-stone-300" required>
                <input name="email" type="email" placeholder="Email" class="rounded-sm border-stone-300 md:col-span-2">
                <textarea name="message" rows="6" placeholder="Zpráva" class="rounded-sm border-stone-300 md:col-span-2" required></textarea>
            </div>
            <button class="mt-5 rounded-sm bg-stone-950 px-6 py-3 text-sm font-bold text-white hover:bg-rose-800" type="submit">Odeslat dotaz</button>
        </form>
    </div>
</section>
@endsection
