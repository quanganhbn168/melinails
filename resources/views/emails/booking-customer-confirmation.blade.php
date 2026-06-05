<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>{{ $mailSubject }}</title>
    <style>
        body { margin: 0; background: #f7f1e6; color: #1f1b16; font-family: Arial, sans-serif; }
        .container { max-width: 620px; margin: 0 auto; padding: 28px 18px; }
        .card { background: #fffdf8; border: 1px solid #eadfce; border-radius: 14px; padding: 26px; }
        h1 { margin: 0 0 12px; font-size: 24px; }
        p { line-height: 1.6; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; }
        td { padding: 10px 0; border-bottom: 1px solid #eee4d7; vertical-align: top; }
        td:first-child { width: 150px; color: #746b60; font-weight: bold; }
        .code { display: inline-block; margin: 12px 0; padding: 8px 12px; background: #15120f; color: #fff; border-radius: 6px; letter-spacing: .04em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <h1>Meli Nails & Beauty</h1>
            <p>{!! nl2br(e($mailBody)) !!}</p>
            <div class="code">{{ $appointment->code }}</div>
            <table>
                <tr><td>Termín</td><td>{{ $appointment->starts_at->format('d.m.Y H:i') }} - {{ $appointment->ends_at->format('H:i') }}</td></tr>
                <tr><td>Pobočka</td><td>{{ $appointment->branch?->name }}<br>{{ $appointment->branch?->address }}</td></tr>
                <tr>
                    <td>Služby</td>
                    <td>
                        @forelse($appointment->segments as $segment)
                            <div>
                                {{ $segment->starts_at->format('H:i') }} - {{ $segment->ends_at->format('H:i') }}:
                                <strong>{{ $segment->service_name }}</strong>
                                <br>
                                {{ $segment->staff?->name ?? 'Tým Meli Nails' }}
                            </div>
                            @if(! $loop->last)<br>@endif
                        @empty
                            {{ collect($appointment->service_snapshot ?? [])->pluck('name')->join(', ') }}
                        @endforelse
                    </td>
                </tr>
                <tr><td>Celkový čas</td><td>{{ $appointment->total_duration_minutes }} min</td></tr>
                <tr><td>Cena</td><td>{{ number_format($appointment->total_price, 0, ',', ' ') }} Kč</td></tr>
            </table>
            @if($appointment->customer_note)
                <p><strong>Poznámka:</strong><br>{{ $appointment->customer_note }}</p>
            @endif
            <p>Pokud potřebujete termín změnit, kontaktujte prosím salon telefonicky.</p>
        </div>
    </div>
</body>
</html>
