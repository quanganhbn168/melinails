@extends('layouts.master')

@section('title', $service->name . ' | Meli Nails & Beauty')
@section('meta_description', $service->description)

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => $service->category?->name,
    'title' => $service->name,
    'text' => $service->description
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-8 lg:grid-cols-[1fr_380px]">
        <article class="rounded-sm border border-stone-200 bg-white p-6 md:p-8">
            <h2 class="text-3xl font-bold text-stone-950">Jak služba probíhá?</h2>
            <div class="mt-5 space-y-4 text-base leading-8 text-stone-600">
                <p>{{ $service->content ?: $service->description }}</p>
                <p>Rezervace může později ověřovat dostupnost podle pobočky, specialistky, délky služby a individuální ceny v daném salonu.</p>
            </div>

            <h3 class="mt-10 text-2xl font-bold text-stone-950">Dostupnost v pobočkách</h3>
            <div class="mt-5 grid gap-4 md:grid-cols-2">
                @foreach($service->branches as $branch)
                    <div class="rounded-sm bg-[#fff7f7] p-5">
                        <strong class="block text-stone-950">{{ $branch->name }}</strong>
                        <span class="mt-2 block text-sm text-stone-600">{{ $branch->pivot->price_text ?? number_format($branch->pivot->price, 0, ',', ' ') . ' Kč' }} • {{ $branch->pivot->duration_minutes ?? $service->duration_minutes }} min</span>
                    </div>
                @endforeach
            </div>
        </article>

        <aside class="rounded-sm border border-stone-200 bg-white p-6 shadow-sm lg:sticky lg:top-28 lg:self-start">
            <h2 class="text-2xl font-bold text-stone-950">Rezervace</h2>
            <p class="mt-3 text-sm leading-6 text-stone-600">Vyberte pobočku a čas. Booking je zatím frontend základ připravený pro napojení na backend.</p>
            <a href="{{ route('meli.booking', ['service' => $service->code]) }}" class="mt-5 inline-flex w-full justify-center rounded-sm bg-stone-950 px-5 py-3 text-sm font-bold text-white hover:bg-rose-800">Rezervovat službu</a>
        </aside>
    </div>
</section>
@endsection
