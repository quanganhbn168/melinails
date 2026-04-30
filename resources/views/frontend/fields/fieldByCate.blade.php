@extends('layouts.master')

@section('title', $pageTitle)

@section('content')

<x-frontend.leaderboard
    :image="$bannerUrl"
    :title="$pageTitle"
    subline="Lĩnh vực hoạt động"
    :description="$current_category->description"
    :breadcrumb="$breadcrumbs"
/>

<section class="bg-gray-50 dark:bg-gray-900 py-16 md:py-24">
    <div class="max-w-screen-xl mx-auto px-4">

        <div class="text-center max-w-3xl mx-auto mb-16">
            <h2 class="text-2xl md:text-3xl font-black text-gray-900 mb-6 uppercase tracking-wider">
                <span class="block">Những thách thức của ngành</span>
                <span class="block text-brand-600">
                    {{ $current_category->name }}
                </span>
            </h2>

            <div class="w-16 h-1 bg-brand-600 mx-auto mb-6"></div>

            @if(!empty($current_category->description))
                <p class="text-lg text-gray-600 dark:text-gray-400 font-medium">
                    {{ $current_category->description }}
                </p>
            @endif
        </div>

        @if(isset($field_categories) && $field_categories->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($field_categories as $field)
                    <a href="{{ $field->url ?? '#' }}"
                       class="group bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-xl transition-all duration-300">

                        <div class="aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-700">
                            <img
                                src="{{ $field->image?->url }}"
                                alt="{{ $field->name }}"
                                class="h-full w-full object-cover group-hover:scale-105 transition-transform duration-500"
                            >
                        </div>

                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3">
                                {{ $field->name }}
                            </h3>

                            @if(!empty($field->description))
                                <p class="text-gray-600 dark:text-gray-400 line-clamp-3">
                                    {{ $field->description }}
                                </p>
                            @endif

                            <div class="mt-5 text-brand-600 font-semibold">
                                Xem chi tiết →
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-700 shadow-sm">
                <i class="fas fa-folder-open text-5xl text-gray-300 dark:text-gray-600 mb-4"></i>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                    Nội dung đang cập nhật
                </h3>
                <p class="text-gray-500 dark:text-gray-400">
                    Danh mục này hiện chưa có danh mục con.
                </p>
            </div>
        @endif

    </div>
</section>

@endsection
