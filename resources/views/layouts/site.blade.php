<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Travel Umroh & Haji')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-[var(--color-paper)]" x-data="{ mobileNav: false }" @keydown.escape.window="mobileNav = false">

    @php
        $settings = \App\Models\Setting::pluck('value', 'key');
        $siteName = $settings['site_name'] ?? 'Travel Umroh & Haji';
        $initial = \Illuminate\Support\Str::substr($siteName, 0, 1);
        $navLinks = [
            ['route' => 'packages.index', 'label' => 'Paket'],
            ['route' => 'articles.index', 'label' => 'Artikel'],
            ['route' => 'gallery.index', 'label' => 'Galeri'],
            ['route' => 'faqs.index', 'label' => 'FAQ'],
            ['route' => 'contact.index', 'label' => 'Kontak'],
        ];
        $waNumber = isset($settings['contact_phone']) ? preg_replace('/[^0-9]/', '', $settings['contact_phone']) : null;
        if ($waNumber && str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }
    @endphp

    {{-- ============ NAVBAR ============ --}}
    <header class="sticky top-0 z-50 bg-[var(--color-paper)]/85 backdrop-blur-md border-b border-[var(--color-line)]">
        <div class="max-w-7xl mx-auto px-5 sm:px-8 h-16 sm:h-20 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2.5 shrink-0">
                <img src="{{ asset('images/logo.png') }}" alt="{{ $siteName }}" class="w-9 h-9 object-contain">
                <span class="font-display text-lg sm:text-xl text-[var(--color-ink)]">{{ $siteName }}</span>
            </a>

            <nav class="hidden lg:flex items-center gap-8 text-sm text-[var(--color-ink-soft)]">
                @foreach ($navLinks as $link)
                    @php $isActive = request()->routeIs($link['route']) || request()->routeIs(str($link['route'])->before('.').'.*'); @endphp
                    <a href="{{ route($link['route']) }}"
                       class="relative py-1 transition-colors group {{ $isActive ? 'text-[var(--color-primary)]' : 'hover:text-[var(--color-primary)]' }}">
                        {{ $link['label'] }}
                        <span class="absolute left-0 -bottom-0.5 h-[1.5px] bg-[var(--color-gold)] transition-all duration-300 {{ $isActive ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                @auth
                    @if (auth()->user()->role?->name === 'customer')
                        @include('partials.customer.notification-bell')
                    @endif
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all">Daftar</a>
                @endauth
            </div>

            {{-- Notifikasi + hamburger mobile --}}
            <div class="flex items-center gap-1 lg:hidden">
                @auth
                    @if (auth()->user()->role?->name === 'customer')
                        @include('partials.customer.notification-bell')
                    @endif
                @endauth

                <button @click="mobileNav = !mobileNav" class="p-2 -mr-2 text-[var(--color-ink)]" aria-label="Buka menu">
                    <svg x-show="!mobileNav" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                    </svg>
                    <svg x-show="mobileNav" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Panel menu mobile --}}
        <div x-show="mobileNav" x-cloak x-transition.duration.250ms @click.outside="mobileNav = false"
             class="lg:hidden border-t border-[var(--color-line)] bg-[var(--color-surface)] px-5 py-4 space-y-1">
            @foreach ($navLinks as $link)
                @php $isActive = request()->routeIs($link['route']) || request()->routeIs(str($link['route'])->before('.').'.*'); @endphp
                <a href="{{ route($link['route']) }}" @click="mobileNav = false"
                   class="block px-3 py-2.5 rounded-lg text-sm font-medium transition-colors {{ $isActive ? 'bg-[var(--color-primary)]/8 text-[var(--color-primary)]' : 'text-[var(--color-ink)] hover:bg-[var(--color-paper)]' }}">{{ $link['label'] }}</a>
            @endforeach
            <div class="pt-3 mt-3 border-t border-[var(--color-line)] flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-center px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-center px-5 py-2.5 rounded-full border border-[var(--color-line)] text-sm font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @if (session('success'))
            <div class="max-w-5xl mx-auto px-5 sm:px-8 mt-6">
                <div class="flex items-start gap-2.5 rounded-[var(--radius-card)] border border-[var(--color-success)]/25 bg-[var(--color-success)]/8 text-[var(--color-success)] text-sm px-5 py-3.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif
        @if (session('error'))
            <div class="max-w-5xl mx-auto px-5 sm:px-8 mt-6">
                <div class="flex items-start gap-2.5 rounded-[var(--radius-card)] border border-[var(--color-danger)]/25 bg-[var(--color-danger)]/8 text-[var(--color-danger)] text-sm px-5 py-3.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    {{-- ============ FOOTER ============ --}}
    <footer class="relative overflow-hidden bg-[var(--color-primary-dark)] text-white/60 mt-16">
        <div class="h-px bg-gradient-to-r from-transparent via-[var(--color-gold)]/50 to-transparent"></div>
        <div class="absolute inset-0 opacity-[0.04]" style="background-image:radial-gradient(circle at 1px 1px, white 1px, transparent 0);background-size:24px 24px;"></div>

        <div class="relative max-w-7xl mx-auto px-5 sm:px-8 py-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 text-sm">
            <div>
                <div class="flex items-center gap-2.5 mb-3">
                    <img src="{{ asset('images/logo-white.png') }}" alt="{{ $siteName }}" class="w-8 h-8 object-contain">
                    <span class="font-display text-white">{{ $siteName }}</span>
                </div>
                <p class="leading-relaxed">{{ $settings['site_tagline'] ?? 'Melayani perjalanan umroh & haji dengan hati.' }}</p>
            </div>
            <div>
                <p class="text-white text-xs uppercase tracking-wide mb-3">Tautan</p>
                <ul class="space-y-2">
                    @foreach ($navLinks as $link)
                        <li>
                            <a href="{{ route($link['route']) }}" class="relative inline-block py-0.5 hover:text-white transition-colors group">
                                {{ $link['label'] }}
                                <span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="text-white text-xs uppercase tracking-wide mb-3">Kontak</p>
                <ul class="space-y-2">
                    @if (!empty($settings['contact_phone']))
                        <li>WhatsApp: {{ $settings['contact_phone'] }}</li>
                    @endif
                    @if (!empty($settings['contact_email']))
                        <li>Email: {{ $settings['contact_email'] }}</li>
                    @endif
                    @if (!empty($settings['contact_address']))
                        <li>{{ $settings['contact_address'] }}</li>
                    @endif
                    @if (!empty($settings['operational_hours']))
                        <li>{{ $settings['operational_hours'] }}</li>
                    @endif
                </ul>
            </div>
            <div>
                <p class="text-white text-xs uppercase tracking-wide mb-3">Legalitas</p>
                <p class="leading-relaxed">Terdaftar resmi Kementerian Agama RI. Anggota asosiasi travel umroh &amp; haji nasional.</p>
            </div>
        </div>
        <div class="relative border-t border-white/10 py-5 text-center text-xs">
            &copy; {{ date('Y') }} {{ $siteName }}. Seluruh hak cipta dilindungi.
        </div>
    </footer>

    {{-- ============ TOMBOL MENGAMBANG: Scroll-to-top (tengah) & WhatsApp (kanan) ============ --}}
    <div x-data="{ showTop: false }" @scroll.window.debounce.150ms="showTop = window.scrollY > 480">
        <button type="button" x-show="showTop" x-cloak x-transition.opacity.scale.duration.200ms
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
                aria-label="Kembali ke atas"
                class="fixed left-1/2 -translate-x-1/2 bottom-5 sm:bottom-6 z-40 w-11 h-11 rounded-full bg-[var(--color-surface)] border border-[var(--color-line)] text-[var(--color-primary)] shadow-lg shadow-black/10 flex items-center justify-center hover:-translate-y-0.5 hover:border-[var(--color-primary)]/40 hover:shadow-xl active:scale-95 transition-all duration-200">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" /></svg>
        </button>
    </div>

    @if ($waNumber)
        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" aria-label="Chat via WhatsApp"
           class="group fixed right-4 sm:right-6 bottom-5 sm:bottom-6 z-40 w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg shadow-black/20 flex items-center justify-center hover:-translate-y-0.5 hover:shadow-xl active:scale-95 transition-all duration-200">
            <span class="absolute inset-0 rounded-full bg-[#25D366] animate-ping opacity-40 group-hover:opacity-0"></span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="relative w-7 h-7"><path d="M16.001 3C9.373 3 4 8.373 4 15c0 2.386.7 4.607 1.907 6.47L4 29l7.727-1.868A11.94 11.94 0 0016 27c6.628 0 12-5.373 12-12S22.629 3 16.001 3zm.006 21.75c-1.94 0-3.79-.51-5.39-1.47l-.386-.23-4.585 1.108 1.148-4.47-.252-.397A9.7 9.7 0 016.25 15c0-5.376 4.375-9.75 9.757-9.75 5.375 0 9.743 4.374 9.743 9.75 0 5.377-4.368 9.75-9.743 9.75zm5.35-7.297c-.293-.147-1.734-.856-2.003-.953-.269-.098-.465-.147-.66.147-.196.293-.758.953-.929 1.148-.171.196-.343.22-.636.073-.293-.147-1.236-.456-2.354-1.454-.87-.776-1.457-1.735-1.628-2.028-.171-.293-.018-.451.129-.598.132-.132.293-.343.44-.514.147-.171.196-.293.293-.489.098-.196.049-.367-.024-.514-.073-.147-.66-1.594-.905-2.183-.238-.573-.48-.495-.66-.505a13 13 0 00-.562-.011.99.99 0 00-.734.343c-.245.269-.94.918-.94 2.24 0 1.32.964 2.596 1.098 2.775.134.18 1.9 2.9 4.605 4.066.643.278 1.145.444 1.536.568.645.205 1.232.176 1.696.107.517-.077 1.734-.709 1.979-1.394.244-.685.244-1.271.171-1.394-.073-.122-.269-.196-.562-.343z"/></svg>
        </a>
    @endif

</body>
</html>