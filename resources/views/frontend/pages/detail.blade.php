@extends('layouts.master')
@section('title', $page->title ?? $pageTitle)
@section('meta_description', $page->meta_description ?? '')
@section('meta_keywords', $page->meta_keywords ?? '')
@section('meta_image', $page->metaImage ? $page->metaImage->url : ($bannerUrl ?? ''))

@section('content')

<x-frontend.page-hero 
    :image="$bannerUrl" 
    :title="$pageTitle" 
    :breadcrumb="$breadcrumbs" 
/>

<section class="py-12 md:py-20 bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800">
    <div class="max-w-screen-md mx-auto px-4 sm:px-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-100 dark:border-gray-700 p-8 md:p-12">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                {{ $page->title }}
            </h1>
            
            <div class="prose prose-lg dark:prose-invert max-w-none prose-headings:font-bold prose-a:text-brand-600 dark:prose-a:text-brand-400 hover:prose-a:text-brand-700">
                {!! $page->content !!}
            </div>
            
            <div class="mt-12 pt-8 border-t border-gray-100 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                Cập nhật lần cuối: {{ $page->updated_at ? $page->updated_at->format('d/m/Y') : 'N/A' }}
            </div>
        </div>
    </div>
</section>

@endsection
