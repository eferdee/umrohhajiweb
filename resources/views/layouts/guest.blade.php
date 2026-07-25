@php
    $siteName = \App\Models\Setting::where('key', 'site_name')->value('value') ?? 'Zafa Travel';
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Masuk' }} — {{ $siteName }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">

    <div class="min-h-screen relative flex items-center justify-center px-5 py-10 sm:py-14 overflow-hidden auth-bg">

        {{-- Decorative background: subtle Islamic geometric pattern + radial glow --}}
        <div class="absolute inset-0 auth-pattern pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -top-24 -left-24 w-72 h-72 sm:w-96 sm:h-96 rounded-full auth-glow-primary pointer-events-none" aria-hidden="true"></div>
        <div class="absolute -bottom-28 -right-20 w-80 h-80 sm:w-[26rem] sm:h-[26rem] rounded-full auth-glow-gold pointer-events-none" aria-hidden="true"></div>

        {{-- ============ SINGLE CENTERED AUTH CARD ============ --}}
        <div class="relative w-full max-w-[440px] auth-card-enter">

            {{-- Logo & brand name --}}
            <a href="{{ url('/') }}" class="flex flex-col items-center gap-3 mb-7 group focus:outline-none">
                <span class="w-14 h-14 rounded-2xl bg-[var(--color-primary)] flex items-center justify-center shadow-lg shadow-[var(--color-primary)]/25 ring-1 ring-black/5 transition-transform duration-300 group-hover:scale-105">
                    <img src="{{ asset('images/logo-white.png') }}" alt="{{ $siteName }}" class="w-9 h-9 object-contain">
                </span>
                <span class="font-display text-xl text-[var(--color-ink)] tracking-tight text-center">{{ $siteName }}</span>
                <span class="text-[11px] font-medium uppercase tracking-[0.2em] text-[var(--color-ink-soft)]">Travel Haji &amp; Umroh</span>
            </a>

            {{-- Card --}}
            <div class="relative bg-[var(--color-surface)] rounded-[22px] border border-[var(--color-line)] shadow-[0_24px_60px_-15px_rgba(5,12,105,0.22)] p-7 sm:p-9">

                @if ($title)
                    <h1 class="font-display text-[1.55rem] leading-tight text-[var(--color-ink)] mb-1.5 text-center">{{ $title }}</h1>
                @endif

                @if ($subtitle)
                    <p class="text-sm text-[var(--color-ink-soft)] text-center mb-7 leading-relaxed">{{ $subtitle }}</p>
                @else
                    <div class="mb-7"></div>
                @endif

                {{ $slot }}
            </div>

            <p class="text-center text-xs text-[var(--color-ink-soft)] mt-7">
                &copy; {{ date('Y') }} {{ $siteName }}. Seluruh hak cipta dilindungi.
            </p>
        </div>

    </div>

</body>
</html>
