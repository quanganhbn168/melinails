@extends('layouts.master')
@section('title', $post->title)
@section('meta_description', $post->description ?? Str::limit(strip_tags($post->content), 155))
@section('meta_image', optional($post->mainImage())->url() ?: ($post->image ? asset($post->image) : ''))
@push('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "NewsArticle",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "headline": "{{ $post->title }}",
  "image": [
    "{{ optional($post->mainImage())->url() ?: ($post->image ? asset($post->image) : '') }}"
  ],
  "datePublished": "{{ $post->created_at->toIso8601String() }}",
  "dateModified": "{{ $post->updated_at->toIso8601String() }}",
  "author": {
    "@type": "Person",
    "name": "{{ $setting->name ?? 'Admin' }}",
    "url": "{{ url('/') }}",
    "image": "{{ asset($setting->logo) }}"
  },
  "publisher": {
    "@type": "Organization",
    "name": "{{ $setting->name }}",
    "logo": {
      "@type": "ImageObject",
      "url": "{{ asset($setting->logo) }}"
    }
  },
  "description": "{{ $post->description ?? Str::limit(strip_tags($post->content), 155) }}"
  @if($aggregateRating = $post->getAggregateRatingData())
  ,"aggregateRating": @json($aggregateRating)
  @endif
}
</script>
@endpush


@section('content')
<div class="banner">
	@php
		$bannerUrl = optional($post->bannerImage())->url() ?: ($post->banner ? asset($post->banner) : asset($setting->banner ?? ''));
	@endphp
	<img src="{{ $bannerUrl }}" 
         alt="{{ $post->title }}" 
         class="img-fluid w-100 banner-image" 
         loading="lazy">
</div>
<section class="section py-4">
	<div class="container">
		<div class="row">
			<div class="col-12 col-md-9">
				<article class="post-detail">
					<h1 class="mb-2">{{ $post->title }}</h1>
					<div class="post-image">
						@php
							$mainUrl = optional($post->mainImage())->url() ?: ($post->image ? asset($post->image) : null);
						@endphp
						@if($mainUrl)
							<img src="{{ $mainUrl }}" alt="{{ $post->title }}">
						@endif
					</div>
					<p class="text-muted mb-3">
						<i class="fa-regular fa-calendar"></i> {{ $post->updated_at->format('d/m/Y') }}
					</p>
					<x-social-share :title="$post->title" />

					<hr class="d-lg-none">
					<div class="post-content mt-4 text-justify">
						{!! $contentHtml !!}
					</div>
					{{-- Chia sẻ lại ở cuối bài --}}
					<div class="mt-5">
						<p class="font-weight-bold">Bạn thấy bài viết hữu ích? Chia sẻ ngay:</p>
						<x-social-share :title="$post->title" />
					</div>
				</article>
				{{-- Bài viết liên quan --}}
				@if ($relatedPosts->count())
				<div class="mt-5">
					<h3>Bài viết liên quan</h3>
					<div class="swiper related-post-swiper">
						<div class="swiper-wrapper">
							@foreach ($relatedPosts as $related)
							<div class="swiper-slide">
								<a href="{{ route('frontend.slug.handle', $related->slug) }}" class="d-block">
									<img src="{{ optional($related->mainImage())->url() ??
                                                    (optional($related->bannerImage())->url() ??
                                                        ($related->image ? asset($related->image) : asset('images/no-image.png'))) }}"
										class="img-fluid mb-2" alt="{{ $related->title }}" loading="lazy"
										width="400" height="250">
									<h6>{{ $related->title }}</h6>
								</a>
							</div>
							@endforeach
						</div>
						<div class="swiper-pagination"></div>
					</div>
				</div>
				@endif

				{{-- Bình luận & Đánh giá --}}
				<x-comment-list :comments="$post->approvedComments" />
				<x-comment-form :commentable="$post" type="post" />
			</div>
			<div class="col-12 col-md-3">
				<aside class="post-sidebar">
					<div class="sidebar-widget">
						<h2 class="sidebar-title">Danh mục bài viết</h2>
						<ul class="sidebar-menu">
							@foreach($allCategories as $category)
							<li>
								<a href="{{ route('frontend.slug.handle', $category->slugValue) }}">
									<i class="fas fa-angle-right me-2"></i>{{ $category->name }}
								</a>
							</li>
							@endforeach
						</ul>
					</div>
				</aside>
				
				<div class="sticky-toc">
					<x-toc :list="$tocList" />
				</div>
				
			</div>
		</div>
	</div>
</section>
@endsection

@push('js')
{{-- Chỉ giữ Swiper cho related posts, bỏ JS TOC tự sinh --}}
<script>
	new Swiper(".related-post-swiper", {
		slidesPerView: 2,
		spaceBetween: 20,
		pagination: {
			el: ".swiper-pagination",
			clickable: true
		},
		breakpoints: {
			768: {
				slidesPerView: 3
			},
			1024: {
				slidesPerView: 4
			},
		},
	});
</script>
@endpush