@extends('layouts.master')

@section('title', $category->name . ' | Meli Nails & Beauty')
@section('meta_description', $category->description)

@section('content')
@include('frontend.melinails.partials.page-hero', [
    'eyebrow' => 'Kategorie služeb',
    'title' => $category->name,
    'text' => $category->description
])

<section class="px-4 py-16 sm:px-6 lg:px-8">
    <div class="mx-auto grid max-w-7xl gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse($services as $service)
            @include('frontend.melinails.partials.service-card', ['service' => $service])
        @empty
            <p class="rounded-sm border border-stone-200 bg-white p-6 text-stone-600">V této kategorii zatím nejsou aktivní služby.</p>
        @endforelse
    </div>
</section>
@endsection
