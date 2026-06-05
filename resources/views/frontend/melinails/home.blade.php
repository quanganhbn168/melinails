@extends('layouts.master')

@section('title', 'Meli Nails & Beauty | Salon krásy v Uherském Hradišti a Zlíně')
@section('meta_description', 'Meli Nails & Beauty nabízí manikúru, pedikúru, kosmetiku, řasy, obočí, depilaci a masáže ve dvou pobočkách.')

@section('content')
@php
    $homeCategories = [
        ['title' => 'Manikúra', 'text' => 'Klasická péče, gel lak, modelace i jemné zdobení pro čistý a upravený vzhled.', 'price' => 'od 200 Kč', 'type' => 'nails', 'slug' => 'manikura'],
        ['title' => 'Pedikúra', 'text' => 'Péče o chodidla, gel lak na nohy a komfortní procedury pro lehký pocit.', 'price' => 'od 420 Kč', 'type' => 'nails', 'slug' => 'pedikura'],
        ['title' => 'Řasy', 'text' => 'Prodlužování, doplnění, lash lifting a barvení pro otevřený pohled.', 'price' => 'od 500 Kč', 'type' => 'face', 'slug' => 'oboci-rasy'],
        ['title' => 'Obočí', 'text' => 'Úprava, barvení a laminace obočí pro přirozeně zvýrazněný výraz.', 'price' => 'od 50 Kč', 'type' => 'face', 'slug' => 'oboci-rasy'],
        ['title' => 'Kosmetika', 'text' => 'Čištění pleti, masky, masáže obličeje a cílené ošetření podle typu pleti.', 'price' => 'od 550 Kč', 'type' => 'face', 'slug' => 'kosmetika'],
        ['title' => 'Masáže', 'text' => 'Tradiční vietnamské, relaxační, záda, šíje, hlava, obličej i párové procedury.', 'price' => 'od 450 Kč', 'type' => 'relax', 'slug' => 'masaze'],
        ['title' => 'Depilace', 'text' => 'Šetrná depilace obličeje i těla s důrazem na hladký výsledek.', 'price' => 'od 170 Kč', 'type' => 'relax', 'slug' => 'depilace'],
        ['title' => 'Poukazy', 'text' => 'Dárkové poukazy pro chvíle krásy, relaxace a péče, které potěší.', 'price' => 'dle hodnoty', 'type' => 'relax', 'slug' => 'poukazy'],
    ];
@endphp

<div class="meli-home">
    <section class="relative overflow-hidden bg-stone-950 text-white">
        <div class="swiper meli-hero-swiper">
            <div class="swiper-wrapper">
                @foreach($heroSlides as $slide)
                    <div class="swiper-slide relative min-h-[560px] md:min-h-[680px]">
                        <img src="{{ $slide['image'] }}" alt="" class="absolute inset-0 h-full w-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-stone-950 via-stone-950/70 to-transparent"></div>
                        <div class="relative mx-auto flex min-h-[560px] max-w-7xl items-center px-4 py-16 sm:px-6 md:min-h-[680px] lg:px-8">
                            <div class="max-w-3xl">
                                <div class="text-xs font-bold uppercase tracking-[0.24em] text-rose-100">{{ $slide['eyebrow'] }}</div>
                                <h1 class="mt-5 text-4xl font-bold leading-tight sm:text-6xl lg:text-7xl">{{ $slide['title'] }}</h1>
                                <p class="mt-6 max-w-2xl text-lg leading-8 text-stone-200">{{ $slide['text'] }}</p>
                                <div class="mt-8 flex flex-wrap gap-3">
                                    <a href="{{ route('meli.booking') }}" class="rounded-sm bg-white px-6 py-3 text-sm font-bold text-stone-950 transition hover:bg-rose-100">Rezervovat online</a>
                                    <a href="{{ route('meli.services') }}" class="rounded-sm border border-white/40 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10">Prohlédnout služby</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination !bottom-6"></div>
            <button class="meli-hero-prev absolute left-4 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-sm border border-white/30 bg-white/10 text-white backdrop-blur md:flex" aria-label="Předchozí"><i class="fa-solid fa-chevron-left"></i></button>
            <button class="meli-hero-next absolute right-4 top-1/2 z-10 hidden h-11 w-11 -translate-y-1/2 items-center justify-center rounded-sm border border-white/30 bg-white/10 text-white backdrop-blur md:flex" aria-label="Další"><i class="fa-solid fa-chevron-right"></i></button>
        </div>
    </section>

    <section class="mh-section-compact">
        <div class="mh-container mh-stats-grid">
            <article><strong>6+</strong><p>let profesionální beauty péče a individuálního přístupu</p></article>
            <article><strong>2</strong><p>pobočky v Uherském Hradišti a ve Zlíně</p></article>
            <article><strong>9</strong><p>kategorií služeb od nehtů po relaxační masáže</p></article>
            <article><strong>1</strong><p>místo pro krásu, pohodlí a čas věnovaný sobě</p></article>
        </div>
    </section>

    <section class="mh-section" id="about">
        <div class="mh-container mh-intro-grid">
            <div class="mh-photo-stack" data-aos="fade-right">
                <img class="mh-tall" src="https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=900&q=82" alt="Elegantní beauty portrét" loading="lazy">
                <div>
                    <img src="https://images.unsplash.com/photo-1607779097040-26e80aa78e66?auto=format&fit=crop&w=900&q=82" alt="Kosmetická procedura" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=900&q=82" alt="Relaxace v salonu" loading="lazy">
                </div>
            </div>
            <div data-aos="fade-up">
                <div class="mh-eyebrow">O salonu</div>
                <h2 class="mh-title">Krása, která působí přirozeně a lehce.</h2>
                <p class="mh-text">V Meli Nails & Beauty se každý detail řeší s klidem - od výběru procedury přes čisté prostředí až po finální výsledek. Salon je navržený jako vzdušné místo s jemnou estetikou, kde se klientka cítí vítaná a opečovaná.</p>
                <div class="mh-feature-list">
                    <article><span>01</span><div><strong>Individuální přístup</strong><p>Pomůžeme vybrat proceduru podle vašich potřeb, typu pleti, stylu i času.</p></div></article>
                    <article><span>02</span><div><strong>Šetrná a ověřená péče</strong><p>Důraz na hygienu, kvalitní produkty a přirozeně elegantní výsledek.</p></div></article>
                    <article><span>03</span><div><strong>Komplexní beauty koncept</strong><p>Manikúra, pedikúra, řasy, obočí, kosmetika, masáže, depilace i poukazy.</p></div></article>
                </div>
            </div>
        </div>
    </section>

    <section class="mh-section mh-section-cream" id="services">
        <div class="mh-container">
            <div class="mh-services-head">
                <div>
                    <div class="mh-eyebrow">Služby</div>
                    <h2 class="mh-title">Vyberte si péči podle nálady, času i potřeby.</h2>
                    <p class="mh-text">Na homepage ukazujeme hlavní kategorie. Detailní ceník a rozsah služeb je připravený v samostatné sekci.</p>
                </div>
                <a class="mh-btn mh-btn-secondary" href="{{ route('meli.prices') }}">Orientační ceník</a>
            </div>
            <div class="mh-services-grid">
                @foreach($homeCategories as $item)
                    <a class="mh-service-card" href="{{ route('meli.services') }}">
                        <div class="mh-service-icon"><i class="fa-solid fa-spa"></i></div>
                        <h3>{{ $item['title'] }}</h3>
                        <p>{{ $item['text'] }}</p>
                        <span>{{ $item['price'] }} -></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mh-section-compact">
        <div class="mh-container">
            <div class="mh-editorial" data-aos="zoom-in">
                <div>
                    <div class="mh-eyebrow">Beauty concept</div>
                    <h2>Vzdušný evropský styl, méně hluku, více elegance.</h2>
                    <p class="mh-text">Prémiový web pro salon krásy nepotřebuje přeplněné bloky. Stačí kvalitní fotky, výrazná typografie, pomalé animace a čisté CTA.</p>
                </div>
                <div class="mh-quote">„Když má layout prostor dýchat, působí salon dražší a důvěryhodnější.“</div>
            </div>
        </div>
    </section>

    <section class="mh-section" id="prices">
        <div class="mh-container mh-price-preview">
            <aside class="mh-price-nav" data-aos="fade-right">
                <div class="mh-eyebrow">Ceník</div>
                <h3>Orientační ceny</h3>
                <p>Na production webu je kompletní ceník jako samostatná podstránka. Na homepage necháváme jen výběr nejžádanějších položek.</p>
                <div class="mh-btn-row">
                    <a class="mh-btn mh-btn-primary" href="{{ route('meli.booking') }}">Rezervovat</a>
                    <a class="mh-btn mh-btn-secondary" href="{{ route('meli.branches') }}">Vybrat pobočku</a>
                </div>
            </aside>
            <div class="mh-price-list" data-aos="fade-up">
                @foreach($featuredServices->take(7) as $service)
                    <div><span><strong>{{ $service->name }}</strong><small>{{ $service->category?->name }} • {{ $service->duration_minutes }} min</small></span><b>{{ $service->branches->first()?->pivot?->price_text ?? number_format($service->price, 0, ',', ' ') . ' Kč' }}</b></div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mh-section mh-section-cream" id="gallery">
        <div class="mh-container">
            <div class="mh-eyebrow">Galerie</div>
            <h2 class="mh-title">Atmosféra salonu a detaily, které mluví samy.</h2>
            <p class="mh-text">Pro ostrou verzi webu je ideální doplnit reálné fotky interiéru, práce týmu a detailů služeb.</p>
            <div class="mh-gallery-grid">
                <figure class="large"><img src="https://images.unsplash.com/photo-1512496015851-a90fb38ba796?auto=format&fit=crop&w=1100&q=82" alt="Kosmetický portrét" loading="lazy"></figure>
                <figure><img src="https://images.unsplash.com/photo-1583001809873-a128495da465?auto=format&fit=crop&w=1000&q=82" alt="Péče o pleť" loading="lazy"></figure>
                <figure><img src="https://images.unsplash.com/photo-1519014816548-bf5fe059798b?auto=format&fit=crop&w=1000&q=82" alt="Detail nehtů" loading="lazy"></figure>
                <figure class="wide"><img src="https://images.unsplash.com/photo-1616394584738-fc6e612e71b9?auto=format&fit=crop&w=1400&q=82" alt="Beauty procedura" loading="lazy"></figure>
            </div>
        </div>
    </section>

    <section class="mh-section" id="branches">
        <div class="mh-container">
            <div class="mh-eyebrow">Pobočky</div>
            <h2 class="mh-title">Dvě adresy, stejná péče a stejný klid.</h2>
            <p class="mh-text">Vyberte si pobočku, která je vám blíž. Kontakty jsou připravené pro rychlé volání, e-mail i navigaci.</p>
            <div class="mh-branches-grid">
                @foreach($meliBranches as $branch)
                    @php
                        $mapEmbedUrl = $branch->map_url ?: 'https://www.google.com/maps?q=' . rawurlencode($branch->address) . '&output=embed';
                        $mapNavigateUrl = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($branch->address);
                    @endphp
                    <article>
                        <div>
                            <h3>{{ $branch->city }}</h3>
                            <p>{{ $branch->address }}</p>
                            <div class="mh-branch-meta">
                                <div><b>⌖</b><span>{{ $branch->address }}</span></div>
                                <div><b>☎</b><a href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}">{{ $branch->phone }}</a></div>
                                <div><b>✉</b><a href="mailto:{{ $branch->email }}">{{ $branch->email }}</a></div>
                                <div><b>◷</b><span>Po-Ne | {{ substr($branch->opening_time, 0, 5) }}-{{ substr($branch->closing_time, 0, 5) }}</span></div>
                            </div>
                            <div class="mh-branch-map">
                                <iframe
                                    src="{{ $mapEmbedUrl }}"
                                    title="Mapa {{ $branch->name }}"
                                    loading="lazy"
                                    referrerpolicy="no-referrer-when-downgrade"
                                ></iframe>
                            </div>
                        </div>
                        <div class="mh-btn-row">
                            <a class="mh-btn mh-btn-primary" href="{{ route('meli.booking', ['branch' => $branch->slug]) }}">Rezervovat</a>
                            <a class="mh-btn mh-btn-secondary" href="tel:{{ preg_replace('/\s+/', '', $branch->phone) }}">Zavolat</a>
                            <a class="mh-btn mh-btn-secondary" href="{{ $mapNavigateUrl }}" target="_blank" rel="noopener">Navigace</a>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mh-section mh-section-cream">
        <div class="mh-container">
            <div class="mh-eyebrow">Recenze</div>
            <h2 class="mh-title">Klientky se vrací kvůli detailu.</h2>
            <div class="mh-reviews-grid">
                <article><div>★★★★★</div><p>„Krásná práce, příjemný přístup a salon, kde se člověk opravdu cítí dobře.“</p><strong>Klára</strong></article>
                <article><div>★★★★★</div><p>„Manikúra vydržela perfektně a prostředí je čisté, jemné a velmi příjemné.“</p><strong>Anna</strong></article>
                <article><div>★★★★★</div><p>„Skvělé služby na jednom místě. Oceňuji rychlé objednání a profesionální přístup.“</p><strong>Monika</strong></article>
            </div>
        </div>
    </section>

    <section class="mh-section" id="contact">
        <div class="mh-container">
            <div class="mh-cta">
                <div class="mh-eyebrow">Rezervace & kontakt</div>
                <h2>Dopřejte si termín, který patří jen vám.</h2>
                <p>Objednejte se telefonicky, e-mailem nebo přes online rezervaci. Vyberte pobočku, službu a čas.</p>
                <div class="mh-btn-row">
                    <a class="mh-btn mh-btn-light" href="{{ route('meli.booking') }}">Rezervovat online</a>
                    <a class="mh-btn mh-btn-ghost" href="{{ route('meli.contact') }}">Kontakt</a>
                </div>
            </div>
        </div>
    </section>
</div>

@push('css')
<style>
    .meli-home {
        --mh-bg: #f7f1e6;
        --mh-bg-2: #fbf8f2;
        --mh-paper: #fffdf8;
        --mh-paper-2: #f1e7d8;
        --mh-text: #1f1b16;
        --mh-muted: #746b60;
        --mh-gold: #d8bd7e;
        --mh-gold-dark: #ac8449;
        --mh-black: #15120f;
        --mh-line: rgba(31, 27, 22, .09);
        --mh-shadow: 0 28px 90px rgba(31, 27, 22, .09);
        --mh-small-shadow: 0 14px 40px rgba(31, 27, 22, .08);
        color: var(--mh-text);
        background:
            radial-gradient(circle at 8% 8%, rgba(216,189,126,.20), transparent 28%),
            radial-gradient(circle at 92% 12%, rgba(172,132,73,.10), transparent 30%),
            linear-gradient(180deg, var(--mh-bg), var(--mh-bg-2));
        font-family: Manrope, ui-sans-serif, system-ui, sans-serif;
        overflow: hidden;
    }
    .meli-home h1,.meli-home h2,.meli-home h3,.mh-quote,.mh-stats-grid strong { font-family: Georgia, 'Times New Roman', serif; letter-spacing: -.035em; line-height: .98; font-weight: 600; }
    .mh-container { width: min(1240px, calc(100% - 48px)); margin-inline: auto; }
    .mh-section { position: relative; padding: 118px 0; }
    .mh-section-compact { padding: 82px 0; }
    .mh-section-cream { background: rgba(255,255,255,.32); border-block: 1px solid rgba(31,27,22,.045); }
    .mh-eyebrow { display: inline-flex; align-items: center; gap: 13px; margin-bottom: 20px; font-size: 12px; letter-spacing: .18em; text-transform: uppercase; color: var(--mh-gold-dark); font-weight: 800; }
    .mh-eyebrow::before { content: ""; width: 34px; height: 1px; background: currentColor; opacity: .7; }
    .mh-title { font-size: clamp(42px, 5vw, 78px); max-width: 850px; margin-bottom: 20px; }
    .mh-text { max-width: 760px; color: var(--mh-muted); font-size: clamp(16px, 1.35vw, 18px); line-height: 1.75; }
    .mh-btn-row { display: flex; flex-wrap: wrap; gap: 14px; margin-top: 30px; }
    .mh-btn { min-height: 54px; padding: 0 24px; border-radius: 999px; border: 1px solid transparent; display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-size: 14px; font-weight: 800; white-space: nowrap; transition: transform .25s, background .25s, color .25s, box-shadow .25s; }
    .mh-btn:hover { transform: translateY(-2px); }
    .mh-btn-primary { background: var(--mh-black); color: white; box-shadow: var(--mh-small-shadow); }
    .mh-btn-secondary { background: rgba(255,255,255,.48); color: var(--mh-text); border-color: var(--mh-line); }
    .mh-btn-light { background: white; color: var(--mh-black); }
    .mh-btn-ghost { border-color: rgba(255,255,255,.24); color: white; background: rgba(255,255,255,.06); }
    .mh-hero { padding: 70px 0 88px; }
    .mh-hero-grid { display: grid; grid-template-columns: 1fr 1.02fr; gap: clamp(42px, 6vw, 88px); align-items: center; }
    .mh-hero h1 { font-size: clamp(58px, 7.5vw, 112px); max-width: 760px; margin-bottom: 24px; }
    .mh-hero p { color: var(--mh-muted); max-width: 650px; font-size: clamp(17px, 1.45vw, 19px); line-height: 1.75; }
    .mh-meta { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 30px; }
    .mh-meta span { display: inline-flex; align-items: center; min-height: 40px; padding: 0 15px; border-radius: 999px; border: 1px solid var(--mh-line); background: rgba(255,255,255,.52); color: var(--mh-muted); font-size: 13px; font-weight: 800; }
    .mh-hero-visual { position: relative; min-height: 720px; }
    .mh-visual-swiper { position: absolute; right: 0; top: 0; width: 84%; height: 78%; overflow: hidden; border-radius: 42px; background: var(--mh-paper-2); box-shadow: var(--mh-shadow); }
    .mh-photo img { width: 100%; height: 100%; object-fit: cover; }
    .mh-photo-main { width: 100%; height: 100%; }
    .mh-photo-small { position: absolute; left: 0; bottom: 0; width: 48%; height: 42%; overflow: hidden; border-radius: 42px; border: 12px solid var(--mh-bg); background: var(--mh-paper-2); box-shadow: var(--mh-shadow); }
    .mh-hero-card { position: absolute; left: 28px; top: 28px; max-width: 290px; padding: 20px 22px; border: 1px solid rgba(31,27,22,.07); background: rgba(255,255,255,.84); backdrop-filter: blur(16px); border-radius: 24px; box-shadow: var(--mh-small-shadow); z-index: 3; }
    .mh-hero-card strong { display: block; margin-bottom: 6px; font-size: 16px; }
    .mh-hero-card p { font-size: 14px; line-height: 1.7; color: var(--mh-muted); }
    .mh-mark { position: absolute; right: 28px; bottom: 28px; width: 112px; height: 112px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,255,255,.78); border: 1px solid rgba(31,27,22,.07); box-shadow: var(--mh-small-shadow); z-index: 4; }
    .mh-mark img { width: 74px; height: 74px; object-fit: contain; }
    .mh-hero-pagination { bottom: 18px !important; }
    .mh-hero-pagination .swiper-pagination-bullet-active { background: var(--mh-gold-dark); }
    .mh-stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 18px; }
    .mh-stats-grid article { min-height: 164px; padding: 30px; border: 1px solid var(--mh-line); border-radius: 30px; background: rgba(255,255,255,.5); }
    .mh-stats-grid strong { font-size: 56px; display: block; margin-bottom: 10px; }
    .mh-stats-grid p,.mh-feature-list p,.mh-price-nav p { color: var(--mh-muted); font-size: 14px; line-height: 1.7; }
    .mh-intro-grid { display: grid; grid-template-columns: .95fr 1.05fr; gap: clamp(42px, 6vw, 78px); align-items: center; }
    .mh-photo-stack { display: grid; grid-template-columns: 1fr .82fr; gap: 18px; align-items: end; }
    .mh-photo-stack img { width: 100%; object-fit: cover; border-radius: 42px; box-shadow: var(--mh-shadow); background: var(--mh-paper-2); }
    .mh-photo-stack .mh-tall { height: 640px; }
    .mh-photo-stack div { display: grid; gap: 18px; }
    .mh-photo-stack div img { height: 300px; }
    .mh-feature-list { display: grid; gap: 16px; margin-top: 34px; }
    .mh-feature-list article { display: grid; grid-template-columns: 42px 1fr; gap: 16px; padding: 22px; border: 1px solid var(--mh-line); border-radius: 24px; background: rgba(255,255,255,.52); }
    .mh-feature-list span { width: 42px; height: 42px; border-radius: 50%; display: grid; place-items: center; background: var(--mh-black); color: white; font-weight: 800; font-size: 13px; }
    .mh-services-head { display: flex; justify-content: space-between; align-items: end; gap: 30px; margin-bottom: 54px; }
    .mh-services-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .mh-service-card { padding: 28px; min-height: 288px; border: 1px solid var(--mh-line); border-radius: 30px; background: rgba(255,255,255,.58); transition: transform .25s, box-shadow .25s, background .25s; }
    .mh-service-card:hover { transform: translateY(-6px); background: var(--mh-paper); box-shadow: var(--mh-shadow); }
    .mh-service-icon { width: 54px; height: 54px; display: grid; place-items: center; border-radius: 18px; border: 1px solid rgba(31,27,22,.06); background: #f3eadc; margin-bottom: 26px; color: var(--mh-gold-dark); }
    .mh-service-card h3 { font-size: 35px; margin-bottom: 12px; }
    .mh-service-card p { color: var(--mh-muted); font-size: 15px; line-height: 1.7; }
    .mh-service-card span { display: inline-flex; margin-top: 22px; color: var(--mh-gold-dark); font-weight: 900; font-size: 13px; }
    .mh-editorial { padding: 64px; border-radius: 42px; background: var(--mh-paper); border: 1px solid var(--mh-line); box-shadow: var(--mh-shadow); display: grid; grid-template-columns: 1.05fr .95fr; gap: 42px; align-items: center; }
    .mh-editorial h2 { font-size: clamp(40px, 4.5vw, 68px); margin-bottom: 18px; }
    .mh-quote { padding: 30px; border-radius: 30px; background: #f5efe6; border: 1px solid var(--mh-line); font-size: 34px; line-height: 1.1; }
    .mh-price-preview { display: grid; grid-template-columns: .9fr 1.1fr; gap: 34px; align-items: start; }
    .mh-price-nav { position: sticky; top: 104px; padding: 28px; border-radius: 30px; background: rgba(255,255,255,.58); border: 1px solid var(--mh-line); }
    .mh-price-nav h3 { font-family: Georgia, serif; font-size: 38px; margin-bottom: 14px; }
    .mh-price-list { display: grid; gap: 14px; }
    .mh-price-list div { display: grid; grid-template-columns: 1fr auto; gap: 16px; padding: 20px 22px; border-radius: 20px; border: 1px solid var(--mh-line); background: rgba(255,255,255,.62); }
    .mh-price-list strong { display: block; }
    .mh-price-list small { color: var(--mh-muted); font-size: 14px; }
    .mh-price-list b { white-space: nowrap; }
    .mh-gallery-grid { display: grid; grid-template-columns: 1.12fr .88fr 1fr; grid-auto-rows: 270px; gap: 18px; margin-top: 54px; }
    .mh-gallery-grid figure { border-radius: 30px; overflow: hidden; box-shadow: var(--mh-shadow); background: var(--mh-paper-2); }
    .mh-gallery-grid img { width: 100%; height: 100%; object-fit: cover; }
    .mh-gallery-grid .large { grid-row: span 2; }
    .mh-gallery-grid .wide { grid-column: span 2; }
    .mh-branches-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 24px; margin-top: 54px; }
    .mh-branches-grid article { min-height: 390px; padding: 36px; border-radius: 30px; background: rgba(255,255,255,.64); border: 1px solid var(--mh-line); box-shadow: var(--mh-shadow); display: flex; flex-direction: column; justify-content: space-between; }
    .mh-branches-grid h3 { font-size: 46px; margin-bottom: 12px; }
    .mh-branches-grid p { color: var(--mh-muted); }
    .mh-branch-meta { display: grid; gap: 12px; margin: 24px 0 28px; }
    .mh-branch-meta div { display: grid; grid-template-columns: 24px 1fr; gap: 10px; color: var(--mh-muted); font-size: 15px; }
    .mh-branch-meta b { color: var(--mh-gold-dark); }
    .mh-branch-map { overflow: hidden; height: 260px; border-radius: 24px; border: 1px solid var(--mh-line); background: var(--mh-paper-2); }
    .mh-branch-map iframe { display: block; width: 100%; height: 100%; border: 0; filter: saturate(.86) contrast(.96); }
    .mh-reviews-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; margin-top: 54px; }
    .mh-reviews-grid article { padding: 30px; border-radius: 30px; background: rgba(255,255,255,.58); border: 1px solid var(--mh-line); }
    .mh-reviews-grid div { color: var(--mh-gold-dark); letter-spacing: 4px; margin-bottom: 18px; }
    .mh-reviews-grid p { color: var(--mh-muted); margin-bottom: 22px; }
    .mh-cta { overflow: hidden; border-radius: 42px; padding: 76px 62px; background: linear-gradient(120deg, rgba(16,13,10,.86), rgba(16,13,10,.70)), url("https://images.unsplash.com/photo-1515377905703-c4788e51af15?auto=format&fit=crop&w=1800&q=82") center/cover; color: white; box-shadow: var(--mh-shadow); }
    .mh-cta h2 { font-family: Georgia, serif; font-size: clamp(44px, 5.5vw, 82px); max-width: 840px; margin-bottom: 20px; }
    .mh-cta p { max-width: 760px; color: rgba(255,255,255,.78); font-size: 17px; line-height: 1.8; }
    @media (max-width: 1120px) {
        .mh-hero-grid,.mh-intro-grid,.mh-editorial,.mh-price-preview { grid-template-columns: 1fr; }
        .mh-hero-visual { min-height: 660px; }
        .mh-stats-grid,.mh-services-grid,.mh-reviews-grid { grid-template-columns: repeat(2, 1fr); }
        .mh-price-nav { position: static; }
    }
    @media (max-width: 760px) {
        .mh-container { width: min(1240px, calc(100% - 30px)); }
        .mh-section { padding: 86px 0; }
        .mh-section-compact { padding: 62px 0; }
        .mh-hero { padding-top: 44px; }
        .mh-hero h1 { font-size: clamp(52px, 15vw, 72px); }
        .mh-hero-visual { min-height: 520px; }
        .mh-visual-swiper { width: 100%; height: 70%; }
        .mh-photo-small { width: 56%; height: 34%; border-width: 8px; }
        .mh-hero-card { left: 16px; top: 16px; max-width: 245px; padding: 16px; }
        .mh-mark { width: 92px; height: 92px; right: 16px; bottom: 16px; }
        .mh-mark img { width: 60px; height: 60px; }
        .mh-stats-grid,.mh-services-grid,.mh-branches-grid,.mh-reviews-grid { grid-template-columns: 1fr; }
        .mh-services-head { display: block; }
        .mh-photo-stack { grid-template-columns: 1fr; }
        .mh-photo-stack .mh-tall,.mh-photo-stack div img { height: 360px; }
        .mh-editorial { padding: 34px 22px; }
        .mh-quote { font-size: 28px; }
        .mh-gallery-grid { grid-template-columns: 1fr; grid-auto-rows: 260px; }
        .mh-gallery-grid .large,.mh-gallery-grid .wide { grid-row: auto; grid-column: auto; }
        .mh-cta { padding: 48px 24px; border-radius: 32px; }
    }
</style>
@endpush

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (window.Swiper) {
            new window.Swiper('.meli-hero-swiper', {
                loop: true,
                speed: 800,
                autoplay: { delay: 5200, disableOnInteraction: false },
                pagination: { el: '.meli-hero-swiper .swiper-pagination', clickable: true },
                navigation: { nextEl: '.meli-hero-next', prevEl: '.meli-hero-prev' },
            });
        }
    });
</script>
@endpush
@endsection
