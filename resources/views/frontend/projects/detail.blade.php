@extends('layouts.master')
@section('title', $pageTitle)
@section('meta_description', $metaDescription ?? '')
@section('meta_image', $project->banner ? asset($project->banner->url) : ($project->image ? asset($project->image->url) : ''))

@push('jsonld')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "CreativeWork",
  "mainEntityOfPage": {
    "@type": "WebPage",
    "@id": "{{ url()->current() }}"
  },
  "name": "{{ $project->name }}",
  "image": "{{ $project->banner ? asset($project->banner->url) : ($project->image ? asset($project->image->url) : '') }}",
  "dateCreated": "{{ $project->created_at->toIso8601String() }}",
  "dateModified": "{{ $project->updated_at->toIso8601String() }}",
  "creator": {
    "@type": "Organization",
    "name": "{{ $setting->site_name ?? config('app.name') }}",
    "url": "{{ url('/') }}",
    "image": "{{ asset($setting->logo) }}"
  },
  "description": "{{ $metaDescription ?? '' }}"
  @if($aggregateRating = $project->getAggregateRatingData())
  ,"aggregateRating": @json($aggregateRating)
  @endif
}
</script>
@endpush

@section('content')

<x-frontend.leaderboard
    :image="$bannerUrl"
    :title="$pageTitle"
    :subline="$project->category?->name"
    :description="$project->description"
    :breadcrumb="$breadcrumbs"
    :stats="array_values(array_filter([
        $project->year ? ['icon' => 'fas fa-calendar-check', 'value' => $project->year, 'label' => 'Năm triển khai'] : null,
        $project->investor ? ['icon' => 'fas fa-building', 'value' => Str::limit($project->investor, 18), 'label' => 'Chủ đầu tư'] : null,
        $project->address ? ['icon' => 'fas fa-location-dot', 'value' => Str::limit($project->address, 18), 'label' => 'Địa điểm'] : null,
        $project->value ? ['icon' => 'fas fa-chart-line', 'value' => is_numeric($project->value) ? number_format((float) $project->value, 0, ',', '.') : $project->value, 'label' => 'Quy mô'] : null,
    ]))"
/>

<div class="project-detail-page">
    <div class="max-w-screen-xl mx-auto px-4">
        <section class="project-detail-overview" data-aos="fade-up">
            <div class="project-detail-copy">
                <span class="project-detail-kicker">Tổng quan dự án</span>
                <h2>{{ $project->name }}</h2>

                @if($projectOverview)
                    <div class="project-detail-prose">
                        {!! $projectOverview !!}
                    </div>
                @endif
            </div>

            <aside class="project-detail-info">
                @foreach($projectInfo as $info)
                    <div class="project-detail-info-row">
                        <span><i class="{{ $info['icon'] }}"></i></span>
                        <div>
                            <small>{{ $info['label'] }}</small>
                            <strong>{{ $info['value'] }}</strong>
                        </div>
                    </div>
                @endforeach
            </aside>
        </section>

        @if(isset($images) && $images->count() > 0)
            <section class="project-gallery-section" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Hình ảnh dự án</span>
                    <h2>Không gian triển khai thực tế</h2>
                </div>

                <div class="project-gallery-shell">
                    <div class="swiper project-gallery-slider">
                        <div class="swiper-wrapper">
                            @foreach($images as $img)
                                <div class="swiper-slide">
                                    <img src="{{ $img }}" alt="{{ $project->name }}" loading="lazy" decoding="async">
                                </div>
                            @endforeach
                        </div>

                        @if($images->count() > 1)
                            <button type="button" class="project-gallery-nav project-gallery-prev" aria-label="Ảnh trước">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button type="button" class="project-gallery-nav project-gallery-next" aria-label="Ảnh tiếp theo">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                            <div class="project-gallery-pagination swiper-pagination"></div>
                        @endif
                    </div>
                </div>
            </section>
        @endif

        @if($businessProblems->isNotEmpty())
            <section class="project-case-section project-case-problems" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Bài toán doanh nghiệp</span>
                    <h2>Những thách thức trước triển khai</h2>
                </div>

                <div class="project-case-grid">
                    @foreach($businessProblems as $item)
                        <article class="project-case-card">
                            <i class="{{ $item['icon'] ?? 'fas fa-triangle-exclamation' }}"></i>
                            <h3>{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['description']))
                                <p>{{ $item['description'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($implementedSolutions->isNotEmpty())
            <section class="project-case-section project-case-solutions" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Giải pháp triển khai</span>
                    <h2>CNETPOS đã triển khai như thế nào</h2>
                </div>

                <div class="project-solution-list">
                    @foreach($implementedSolutions as $item)
                        <article class="project-solution-item">
                            <span><i class="{{ $item['icon'] ?? 'fas fa-screwdriver-wrench' }}"></i></span>
                            <div>
                                <h3>{{ $item['title'] ?? '' }}</h3>
                                @if(!empty($item['description']))
                                    <p>{{ $item['description'] }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($implementationProcess->isNotEmpty())
            <section class="project-process-section" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Quy trình triển khai</span>
                    <h2>Các bước đưa hệ thống vào vận hành</h2>
                </div>

                <div class="project-process-grid">
                    @foreach($implementationProcess as $item)
                        <article class="project-process-card">
                            <span class="project-process-number">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                            <i class="{{ $item['icon'] ?? 'fas fa-circle-check' }}"></i>
                            <h3>{{ $item['title'] ?? '' }}</h3>
                            @if(!empty($item['description']))
                                <p>{{ $item['description'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if($achievedResults->isNotEmpty())
            <section class="project-results-section" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Kết quả đạt được</span>
                    <h2>Hiệu quả sau triển khai</h2>
                </div>

                <div class="project-results-grid">
                    @foreach($achievedResults as $item)
                        <article class="project-result-card">
                            @if(!empty($item['value']))
                                <strong>{{ $item['value'] }}</strong>
                            @endif
                            <h3>{{ $item['label'] ?? '' }}</h3>
                            @if(!empty($item['description']))
                                <p>{{ $item['description'] }}</p>
                            @endif
                        </article>
                    @endforeach
                </div>
            </section>
        @endif

        @if(!empty($project->content) && trim(strip_tags($project->content)) !== '')
            <section class="project-content-section" data-aos="fade-up">
                <div class="project-section-heading">
                    <span>Thông tin chi tiết</span>
                    <h2>Nội dung triển khai bổ sung</h2>
                </div>

                <div class="project-detail-prose project-detail-prose-box">
                    {!! $project->content !!}
                </div>

                <div class="project-share-row">
                    <x-social-share :title="$project->name" />
                </div>
            </section>
        @endif

        <section class="project-comment-section" data-aos="fade-up">
            <x-comment-list :comments="$project->approvedComments" />
            <x-comment-form :commentable="$project" type="project" />
        </section>

        @if($relatedProjects && $relatedProjects->count() > 0)
    <section class="py-12 lg:py-16" data-aos="fade-up">
        <div class="mb-8 flex items-end justify-between gap-4">
            <div>
                <span class="mb-2 block text-sm font-semibold uppercase tracking-[0.2em] text-primary">
                    Dự án liên quan
                </span>
                <h2 class="text-2xl font-bold text-slate-900 md:text-3xl">
                    Dự án tiêu biểu khác
                </h2>
            </div>

            <div class="hidden items-center gap-2 sm:flex">
                <button
                    type="button"
                    class="project-related-prev inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-primary hover:bg-primary hover:text-white"
                    aria-label="Dự án trước"
                >
                    <i class="fas fa-chevron-left text-sm"></i>
                </button>

                <button
                    type="button"
                    class="project-related-next inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-primary hover:bg-primary hover:text-white"
                    aria-label="Dự án tiếp theo"
                >
                    <i class="fas fa-chevron-right text-sm"></i>
                </button>
            </div>
        </div>

        <div class="swiper project-related-slider !overflow-hidden">
            <div class="swiper-wrapper items-stretch">
                @foreach($relatedProjects as $other)
                    @php
                        $relatedImage = $other->image?->url
                            ? asset($other->image->url)
                            : asset('images/setting/no-image.png');

                        $relatedText = $other->description
                            ?: ($other->investor ? 'Chủ đầu tư: ' . $other->investor : null);
                    @endphp

                    <div class="swiper-slide !h-auto">
                        <a
                            href="{{ $other->slug_url }}"
                            class="group flex h-full overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl"
                        >
                            <span class="block w-[38%] min-w-[120px] shrink-0 overflow-hidden bg-slate-100 sm:w-[42%]">
                                <img
                                    src="{{ $relatedImage }}"
                                    alt="{{ $other->name }}"
                                    loading="lazy"
                                    decoding="async"
                                    class="aspect-square h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                >
                            </span>

                            <span class="flex min-w-0 flex-1 flex-col justify-between p-4 sm:p-5">
                                <span class="block">
                                    @if($other->category)
                                        <em class="mb-2 block text-xs font-semibold not-italic uppercase tracking-wide text-primary">
                                            {{ $other->category->name }}
                                        </em>
                                    @endif

                                    <strong class="line-clamp-2 block text-base font-bold leading-snug text-slate-900 transition group-hover:text-primary sm:text-lg">
                                        {{ $other->name }}
                                    </strong>

                                    @if($relatedText)
                                        <span class="mt-2 line-clamp-2 block text-sm leading-6 text-slate-600">
                                            {{ Str::limit(strip_tags($relatedText), 95) }}
                                        </span>
                                    @endif
                                </span>

                                <span class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-500">
                                    @if($other->year)
                                        <span class="inline-flex items-center gap-1.5">
                                            <i class="fas fa-calendar-check text-primary"></i>
                                            {{ $other->year }}
                                        </span>
                                    @endif

                                    @if($other->address)
                                        <span class="inline-flex min-w-0 items-center gap-1.5">
                                            <i class="fas fa-location-dot text-primary"></i>
                                            <span class="line-clamp-1">
                                                {{ Str::limit($other->address, 24) }}
                                            </span>
                                        </span>
                                    @endif
                                </span>

                                <span class="mt-4 inline-flex items-center gap-2 text-sm font-semibold text-primary">
                                    Xem chi tiết
                                    <i class="fas fa-arrow-right text-xs transition group-hover:translate-x-1"></i>
                                </span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-5 flex items-center justify-center gap-2 sm:hidden">
            <button
                type="button"
                class="project-related-prev inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-primary hover:bg-primary hover:text-white"
                aria-label="Dự án trước"
            >
                <i class="fas fa-chevron-left text-sm"></i>
            </button>

            <button
                type="button"
                class="project-related-next inline-flex size-10 items-center justify-center rounded-full border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-primary hover:bg-primary hover:text-white"
                aria-label="Dự án tiếp theo"
            >
                <i class="fas fa-chevron-right text-sm"></i>
            </button>
        </div>
    </section>
@endif
    </div>

    <x-frontend.page-cta
        :title="$pageSettings->projects_cta_title"
        :description="$pageSettings->projects_cta_description"
        :link="$pageSettings->projects_cta_link"
        image="images/setting/cnetpos-partner.png"
    />
</div>
@endsection

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Swiper === 'undefined') {
        return;
    }

    const galleryEl = document.querySelector('.project-gallery-slider');
    if (galleryEl) {
        const slideCount = galleryEl.querySelectorAll('.swiper-slide').length;
        new Swiper(galleryEl, {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: slideCount > 1,
            speed: 650,
            navigation: {
                nextEl: '.project-gallery-next',
                prevEl: '.project-gallery-prev',
            },
            pagination: {
                el: '.project-gallery-pagination',
                clickable: true,
            },
        });
    }

    const relatedEl = document.querySelector('.project-related-slider');
if (relatedEl) {
    const relatedCount = relatedEl.querySelectorAll('.swiper-slide').length;

    new Swiper(relatedEl, {
        slidesPerView: 1,
        spaceBetween: 18,
        loop: relatedCount > 2,
        speed: 650,
        navigation: {
            nextEl: '.project-related-next',
            prevEl: '.project-related-prev',
        },
        breakpoints: {
            768: { slidesPerView: 1.4, spaceBetween: 20 },
            1024: { slidesPerView: 2, spaceBetween: 22 },
            1280: { slidesPerView: 2.5, spaceBetween: 24 },
        },
    });
}
});
</script>
@endpush
