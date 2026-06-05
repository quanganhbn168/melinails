@extends('layouts.master')

@section('title', 'Rezervace potvrzena | Meli Nails & Beauty')

@section('content')
<section class="booking-thank-page">
    <div class="booking-thank-shell">
        <div class="booking-thank-hero">
            <div class="booking-thank-mark">
                <i class="fa-solid fa-check"></i>
            </div>
            <p class="booking-thank-eyebrow">Rezervace potvrzena</p>
            <h1>Děkujeme, termín máme uložený.</h1>
            <p class="booking-thank-lead">
                Potvrzení rezervace <strong>{{ $appointment->code }}</strong> bylo vytvořeno. Pokud bude salon potřebovat doplnit detail, ozve se vám.
            </p>
            <div class="booking-thank-actions">
                <a href="{{ route('meli.booking') }}" class="booking-thank-button primary">
                    <i class="fa-solid fa-calendar-plus"></i>
                    Vytvořit další rezervaci
                </a>
                <a href="{{ route('meli.contact') }}" class="booking-thank-button secondary">
                    <i class="fa-solid fa-phone"></i>
                    Kontaktovat salon
                </a>
            </div>
        </div>

        <div class="booking-thank-grid">
            <article class="booking-thank-card main">
                <h2>Souhrn termínu</h2>
                <dl>
                    <div>
                        <dt>Kód rezervace</dt>
                        <dd>{{ $appointment->code }}</dd>
                    </div>
                    <div>
                        <dt>Pobočka</dt>
                        <dd>{{ $appointment->branch?->name }}</dd>
                    </div>
                    <div>
                        <dt>Datum a čas</dt>
                        <dd>{{ $appointment->starts_at->format('d.m.Y H:i') }} - {{ $appointment->ends_at->format('H:i') }}</dd>
                    </div>
                    <div>
                        <dt>Celkový čas</dt>
                        <dd>{{ $appointment->total_duration_minutes }} min</dd>
                    </div>
                    <div>
                        <dt>Celková cena</dt>
                        <dd>{{ number_format($appointment->total_price, 0, ',', ' ') }} Kč</dd>
                    </div>
                    <div>
                        <dt>Telefon</dt>
                        <dd>{{ $appointment->customer_phone }}</dd>
                    </div>
                </dl>
            </article>

            <article class="booking-thank-card">
                <h2>Průběh služeb</h2>
                <div class="booking-thank-segments">
                    @foreach($appointment->segments as $segment)
                        <div class="booking-thank-segment">
                            <span>{{ $segment->starts_at->format('H:i') }} - {{ $segment->ends_at->format('H:i') }}</span>
                            <strong>{{ $segment->service_name }}</strong>
                            <p>{{ $segment->staff?->name ?? 'Tým salonu' }}</p>
                        </div>
                    @endforeach
                </div>
            </article>
        </div>
    </div>
</section>

@push('css')
<style>
    .booking-thank-page {
        min-height: 78vh;
        background:
            linear-gradient(180deg, rgba(247, 241, 230, .96), rgba(251, 248, 242, .98)),
            url('https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=1800&q=82') center/cover;
        color: #1f1b16;
        padding: 76px 0 96px;
    }
    .booking-thank-shell {
        width: min(1120px, calc(100% - 36px));
        margin-inline: auto;
    }
    .booking-thank-hero {
        max-width: 820px;
    }
    .booking-thank-mark {
        display: grid;
        width: 62px;
        height: 62px;
        place-items: center;
        border-radius: 50%;
        background: #15120f;
        color: #fffdf8;
        font-size: 24px;
        box-shadow: 0 18px 48px rgba(31, 27, 22, .18);
    }
    .booking-thank-eyebrow {
        margin-top: 22px;
        color: #ac8449;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .18em;
        text-transform: uppercase;
    }
    .booking-thank-hero h1 {
        margin-top: 14px;
        max-width: 760px;
        font-family: Georgia, "Times New Roman", serif;
        font-size: clamp(44px, 7vw, 86px);
        font-weight: 600;
        line-height: 1;
    }
    .booking-thank-lead {
        margin-top: 22px;
        max-width: 680px;
        color: #62594f;
        font-size: 18px;
        line-height: 1.75;
    }
    .booking-thank-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }
    .booking-thank-button {
        display: inline-flex;
        min-height: 48px;
        align-items: center;
        gap: 10px;
        border-radius: 4px;
        padding: 0 18px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none;
    }
    .booking-thank-button.primary {
        background: #15120f;
        color: #fffdf8;
    }
    .booking-thank-button.secondary {
        border: 1px solid rgba(31, 27, 22, .14);
        background: rgba(255, 255, 255, .62);
        color: #1f1b16;
    }
    .booking-thank-grid {
        display: grid;
        grid-template-columns: 1.05fr .95fr;
        gap: 18px;
        margin-top: 44px;
    }
    .booking-thank-card {
        border: 1px solid rgba(31, 27, 22, .09);
        border-radius: 8px;
        background: rgba(255, 253, 248, .78);
        padding: clamp(22px, 4vw, 34px);
        box-shadow: 0 24px 70px rgba(31, 27, 22, .1);
        backdrop-filter: blur(10px);
    }
    .booking-thank-card h2 {
        margin-bottom: 20px;
        font-size: 22px;
        font-weight: 900;
    }
    .booking-thank-card dl {
        display: grid;
        gap: 15px;
    }
    .booking-thank-card dl div {
        display: flex;
        justify-content: space-between;
        gap: 18px;
        border-bottom: 1px solid rgba(31, 27, 22, .08);
        padding-bottom: 13px;
    }
    .booking-thank-card dt {
        color: #746b60;
        font-size: 13px;
    }
    .booking-thank-card dd {
        color: #1f1b16;
        font-weight: 900;
        text-align: right;
    }
    .booking-thank-segments {
        display: grid;
        gap: 12px;
    }
    .booking-thank-segment {
        border-left: 3px solid #ac8449;
        background: rgba(244, 235, 222, .66);
        padding: 14px 16px;
    }
    .booking-thank-segment span {
        color: #8d6837;
        font-size: 12px;
        font-weight: 900;
    }
    .booking-thank-segment strong {
        display: block;
        margin-top: 5px;
        font-size: 16px;
    }
    .booking-thank-segment p {
        margin-top: 3px;
        color: #746b60;
        font-size: 14px;
    }
    @media (max-width: 860px) {
        .booking-thank-grid {
            grid-template-columns: 1fr;
        }
        .booking-thank-card dl div {
            display: block;
        }
        .booking-thank-card dd {
            margin-top: 5px;
            text-align: left;
        }
    }
</style>
@endpush
@endsection
