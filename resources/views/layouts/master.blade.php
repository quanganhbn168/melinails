{{-- resources/views/layouts/master.blade.php --}}
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    {{-- Basic --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Title & SEO --}}
    @php
        $siteName = $setting->site_name ?? config('app.name');
    @endphp
    <title>@yield('title', $setting->site_name)</title>
    <meta name="description" content="@yield('meta_description', $setting->meta_description ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $setting->meta_keywords ?? '')">
    <meta name="robots" content="@yield('meta_robots', 'index, follow')">
    {{-- Canonical --}}
    <link rel="canonical" href="{{ url()->current() }}" />
    {{-- Open Graph --}}
    <meta property="og:type" content="@yield('og_type', 'website')" />
    <meta property="og:title" content="@yield('title', $setting->site_name ?? '')" />
    <meta property="og:description" content="@yield('meta_description', $setting->meta_description ?? '')" />
    <meta property="og:url" content="{{ url()->current() }}" />
    <meta property="og:site_name" content="{{ $setting->site_name ?? config('app.name') }}" />
    <meta property="og:image" content="@yield('meta_image', $globalMetaImageUrl)" />
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="@yield('title', $setting->site_name ?? '')" />
    <meta name="twitter:description" content="@yield('meta_description', $setting->meta_description ?? '')" />
    <meta name="twitter:image" content="@yield('meta_image', $globalMetaImageUrl)" />
    {{-- Fonts, Favicons --}}
    <link rel="icon" href="{{ rtrim($globalFaviconUrl, '?') }}" />
    <link rel="apple-touch-icon" sizes="180x180" href="{{ rtrim($globalFaviconUrl, '?') }}" />

    @stack('head_scripts')

    {{-- Bundle Frontend CSS via Vite (Tailwind v4) --}}
    @vite(['resources/css/frontend.css'])

    @stack('css')
    {!! $setting->head_script ?? '' !!}
    @stack('jsonld')
    @stack('conversion_script')
</head>

<body
    class="bg-[#fff7f7] pb-20 text-stone-900 font-sans antialiased lg:pb-0 {{ Auth::check() ? 'logged-in' : '' }}">
    {!! $setting->body_start_script ?? '' !!}
    

    @include('partials.frontend.header')

    <main id="main-content" class="min-h-screen">
        @yield('content')
    </main>

    @include('partials.frontend.footer')

    <nav class="fixed inset-x-0 bottom-0 z-50 border-t border-stone-200 bg-white/95 px-2 py-2 shadow-2xl backdrop-blur lg:hidden" aria-label="Rychlá mobilní navigace">
        <div class="mx-auto grid max-w-md grid-cols-4 gap-1">
            <a href="{{ route('meli.booking') }}" class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-sm text-[11px] font-bold text-stone-700 {{ request()->routeIs('meli.booking') ? 'bg-rose-50 text-rose-800' : '' }}">
                <i class="fa-solid fa-calendar-days text-base"></i>
                <span>Lịch</span>
            </a>
            <a href="{{ route('meli.services') }}" class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-sm text-[11px] font-bold text-stone-700 {{ request()->routeIs('meli.services') || request()->routeIs('meli.service.*') ? 'bg-rose-50 text-rose-800' : '' }}">
                <i class="fa-solid fa-spa text-base"></i>
                <span>Služby</span>
            </a>
            <a href="tel:+420777768681" class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-sm text-[11px] font-bold text-stone-700">
                <i class="fa-solid fa-phone text-base"></i>
                <span>Zavolat</span>
            </a>
            <a href="{{ route('meli.booking') }}" class="flex min-h-14 flex-col items-center justify-center gap-1 rounded-sm bg-stone-950 text-[11px] font-bold text-white">
                <i class="fa-solid fa-check text-base"></i>
                <span>Booking</span>
            </a>
        </div>
    </nav>

    {{-- Bundle Frontend JS via Vite --}}
    @vite(['resources/js/frontend.js'])

    <script>
        // Back to top logic
        document.addEventListener('DOMContentLoaded', function () {
            const backToTopButton = document.getElementById('js-back-to-top');
            if (backToTopButton) {
                window.addEventListener('scroll', function () {
                    if (window.scrollY > 300) {
                        backToTopButton.classList.remove('hidden');
                        backToTopButton.classList.add('flex');
                    } else {
                        backToTopButton.classList.add('hidden');
                        backToTopButton.classList.remove('flex');
                    }
                });
                backToTopButton.addEventListener('click', function (e) {
                    e.preventDefault();
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }
        });
    </script>
    @stack('js')
    {!! $setting->body_script ?? '' !!}
</body>

</html>
