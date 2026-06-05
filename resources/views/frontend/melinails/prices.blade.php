@extends('layouts.master')

@section('title', 'Ceník | Meli Nails & Beauty')

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Ceník',
    'title' => 'Transparentní ceny pro krásu, péči a relax.',
    'text' => 'Ceny se mohou lišit podle pobočky. Tabulka proto ukazuje cenu za jednotlivé salony.'
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl space-y-8">
        @foreach($categories as $category)
            <div class="overflow-hidden rounded-sm border border-stone-200 bg-white">
                <div class="border-b border-stone-200 bg-[#fff7f7] px-5 py-4">
                    <h2 class="text-2xl font-bold text-stone-950">{{ $category->name }}</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] text-left text-sm">
                        <thead class="bg-stone-50 text-xs uppercase tracking-[0.16em] text-stone-500">
                            <tr>
                                <th class="px-5 py-3">Služba</th>
                                <th class="px-5 py-3">Délka</th>
                                @foreach($meliBranches as $branch)
                                    <th class="px-5 py-3">{{ $branch->city }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @foreach($category->services as $service)
                                <tr>
                                    <td class="px-5 py-4 font-semibold text-stone-900">{{ $service->name }}</td>
                                    <td class="px-5 py-4 text-stone-600">{{ $service->duration_minutes }} min</td>
                                    @foreach($meliBranches as $branch)
                                        @php $branchService = $service->branches->firstWhere('id', $branch->id); @endphp
                                        <td class="px-5 py-4 text-stone-700">
                                            @if($branchService)
                                                {{ $branchService->pivot->price_text ?? number_format($branchService->pivot->price, 0, ',', ' ') . ' Kč' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    </div>
</section>
@endsection
