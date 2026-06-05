@extends('layouts.master')

@section('title', 'Pobočky | Meli Nails & Beauty')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Pobočky',
    'title' => 'Dvě místa pro krásu, péči a klid.',
    'text' => 'Každá pobočka může mít vlastní tým, cenu a nabídku služeb.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-6 lg:grid-cols-2">
        @foreach($meliBranches as $branch)
            @php
                $mapEmbedUrl = $branch->map_url ?: 'https://www.google.com/maps?q=' . rawurlencode($branch->address) . '&output=embed';
                $mapNavigateUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($branch->address);
            @endphp
            <article class="overflow-hidden rounded-sm border border-stone-200 bg-white shadow-sm">
                <div class="p-6">
                    <h2 class="text-3xl font-bold text-stone-950">{{ $branch->name }}</h2>
                    <p class="mt-3 text-stone-600">{{ $branch->address }}</p>
                    <div class="mt-5 space-y-2 text-sm text-stone-700">
                        <p><strong>Telefon:</strong> <a href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}" class="text-rose-700">{{ $branch->phone }}</a></p>
                        <p><strong>Email:</strong> <a href="mailto:{{ $branch->email }}" class="text-rose-700">{{ $branch->email }}</a></p>
                        <p><strong>Otevřeno:</strong> {{ substr($branch->opening_time, 0, 5) }} - {{ substr($branch->closing_time, 0, 5) }}</p>
                        <p><strong>Timezone pro test:</strong> {{ $branch->timezone }}</p>
                    </div>
                    <div class="mt-6 flex flex-wrap gap-2">
                        @foreach($branch->services->take(8) as $service)
                            <span class="rounded-sm bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-800">{{ $service->category?->name ?? $service->name }}</span>
                        @endforeach
                    </div>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('meli.booking', ['branch' => $branch->slug]) }}" class="inline-flex rounded-sm bg-stone-950 px-5 py-3 text-sm font-bold text-white hover:bg-rose-800">Rezervovat v této pobočce</a>
                        <a href="{{ $mapNavigateUrl }}" target="_blank" rel="noopener" class="inline-flex rounded-sm border border-stone-200 bg-white px-5 py-3 text-sm font-bold text-stone-900 hover:bg-stone-50">Otevřít navigaci</a>
                    </div>
                </div>
                <div class="h-[340px] border-t border-stone-200 bg-stone-100">
                    <iframe
                        src="{{ $mapEmbedUrl }}"
                        title="Mapa {{ $branch->name }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        class="h-full w-full border-0"
                    ></iframe>
                </div>
            </article>
        @endforeach
    </div>
</section>
@endsection
