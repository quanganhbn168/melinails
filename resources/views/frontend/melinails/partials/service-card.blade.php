@php
    $categorySlug = $service->category?->slug_value;
    $servicePrice = $service->branches->first()?->pivot?->price_text ?? ($service->price ? number_format($service->price, 0, ',', ' ') . ' Kč' : 'dle hodnoty');
    $serviceDuration = $service->branches->first()?->pivot?->duration_minutes ?? $service->duration_minutes;
@endphp

<article class="group flex h-full flex-col rounded-sm border border-stone-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
    <div class="flex items-start justify-between gap-4">
        <div>
            <div class="text-xs font-bold uppercase tracking-[0.18em] text-rose-700">{{ $service->category?->name }}</div>
            <h3 class="mt-2 text-xl font-bold text-stone-950">{{ $service->name }}</h3>
        </div>
        <span class="rounded-sm bg-stone-100 px-3 py-1 text-sm font-semibold text-stone-700">{{ $serviceDuration }} min</span>
    </div>
    <p class="mt-4 flex-1 text-sm leading-6 text-stone-600">{{ $service->description }}</p>
    <div class="mt-5 flex items-center justify-between gap-4 border-t border-stone-100 pt-4">
        <strong class="text-lg text-stone-950">{{ $servicePrice }}</strong>
        <a href="{{ route('meli.service.detail', $service->slug_value) }}" class="text-sm font-semibold text-rose-700 hover:text-stone-950">
            Detail
        </a>
    </div>
</article>
