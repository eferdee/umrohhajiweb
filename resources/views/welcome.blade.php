<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $settings['site_name'] ?? 'Travel Umroh & Haji' }} — {{ $settings['site_tagline'] ?? 'Umroh & Haji Terpercaya' }}</title>
    <meta name="description" content="{{ $settings['site_tagline'] ?? 'Perjalanan umroh dan haji dengan bimbingan penuh, harga transparan, dan pengalaman ibadah yang tenang.' }}">
    <meta property="og:title" content="{{ $settings['site_name'] ?? 'Travel Umroh & Haji' }}">
    <meta property="og:description" content="{{ $settings['site_tagline'] ?? 'Perjalanan umroh dan haji dengan bimbingan penuh, harga transparan, dan pengalaman ibadah yang tenang.' }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased"
    x-data="{
        mobileNav: false,
        lightboxOpen: false,
        lightboxIndex: 0,
        lightboxItems: {{ Js::from($galleryItems->map(fn ($g) => ['id' => $g->id, 'img' => $g->file_path ? asset('storage/' . $g->file_path) : null, 'title' => $g->title])->values()) }},
        get lightboxImg() { return this.lightboxItems[this.lightboxIndex]?.img ?? null },
        get lightboxTitle() { return this.lightboxItems[this.lightboxIndex]?.title ?? null },
        openLightbox(id) { this.lightboxIndex = this.lightboxItems.findIndex(x => x.id === id); this.lightboxOpen = true },
        nextLightbox() { if (this.lightboxItems.length) this.lightboxIndex = (this.lightboxIndex + 1) % this.lightboxItems.length },
        prevLightbox() { if (this.lightboxItems.length) this.lightboxIndex = (this.lightboxIndex - 1 + this.lightboxItems.length) % this.lightboxItems.length },
    }"
    @keydown.escape.window="lightboxOpen = false; mobileNav = false"
    @keydown.arrow-left.window="lightboxOpen && prevLightbox()"
    @keydown.arrow-right.window="lightboxOpen && nextLightbox()">

    @php
        $siteName = $settings['site_name'] ?? 'Travel Umroh & Haji';
        $initial = Str::substr($siteName, 0, 1);
        $waNumber = isset($settings['contact_phone']) ? preg_replace('/[^0-9]/', '', $settings['contact_phone']) : null;
        if ($waNumber && str_starts_with($waNumber, '0')) {
            $waNumber = '62' . substr($waNumber, 1);
        }
        $navLinks = [
            ['href' => '#paket', 'label' => 'Paket'],
            ['href' => '#alur', 'label' => 'Alur Perjalanan'],
            ['href' => '#artikel', 'label' => 'Artikel'],
            ['href' => '#galeri', 'label' => 'Galeri'],
            ['href' => '#keunggulan', 'label' => 'Keunggulan'],
        ];
        if ($faqs->isNotEmpty()) {
            $navLinks[] = ['href' => '#faq', 'label' => 'FAQ'];
        }
        $navLinks[] = ['href' => '#kontak', 'label' => 'Kontak'];
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
                    <a href="{{ $link['href'] }}" class="relative py-1 hover:text-[var(--color-primary)] transition-colors group">
                        {{ $link['label'] }}
                        <span class="absolute left-0 -bottom-0.5 h-[1.5px] w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span>
                    </a>
                @endforeach
            </nav>

            <div class="hidden lg:flex items-center gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-5 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition-colors">Masuk</a>
                    <a href="{{ route('register') }}" class="px-5 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all">Daftar</a>
                @endauth
            </div>

            {{-- Tombol hamburger mobile --}}
            <button @click="mobileNav = !mobileNav" class="lg:hidden p-2 -mr-2 text-[var(--color-ink)]" aria-label="Buka menu">
                <svg x-show="!mobileNav" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5M3.75 17.25h16.5" />
                </svg>
                <svg x-show="mobileNav" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        {{-- Panel menu mobile --}}
        <div x-show="mobileNav" x-cloak x-transition.duration.250ms @click.outside="mobileNav = false"
             class="lg:hidden border-t border-[var(--color-line)] bg-[var(--color-surface)] px-5 py-4 space-y-1">
            @foreach ($navLinks as $link)
                <a href="{{ $link['href'] }}" @click="mobileNav = false" class="block px-3 py-2.5 rounded-lg text-sm font-medium text-[var(--color-ink)] hover:bg-[var(--color-paper)]">{{ $link['label'] }}</a>
            @endforeach
            <div class="pt-3 mt-3 border-t border-[var(--color-line)] flex flex-col gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="text-center px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-center px-5 py-2.5 rounded-full border border-[var(--color-line)] text-sm font-medium">Masuk</a>
                    <a href="{{ route('register') }}" class="text-center px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    {{-- ============ HERO ============ --}}
    <section class="relative isolate overflow-hidden grain-overlay bg-[linear-gradient(160deg,var(--color-primary-dark)_0%,var(--color-primary)_55%,var(--color-primary-light)_120%)]">
        {{-- Lapisan latar dekoratif — motif lattice khas + blob dengan parallax ringan --}}
        <div class="absolute inset-0 geo-lattice-light opacity-[0.05]"></div>
        <div data-parallax="0.06" class="absolute -right-24 -top-24 w-[26rem] h-[26rem] rounded-full bg-[var(--color-gold)]/20 blur-[100px]"></div>
        <div data-parallax="-0.04" class="absolute -left-32 bottom-0 w-96 h-96 rounded-full bg-[var(--color-primary-light)]/30 blur-[100px]"></div>
        <div data-parallax="0.03" class="absolute left-1/2 top-0 -translate-x-1/2 w-[34rem] h-[34rem] rounded-full bg-white/[0.05] blur-[120px]"></div>
        <x-site.emblem class="hidden lg:block absolute right-[8%] top-[18%] w-6 h-6 text-[var(--color-gold-soft)]/25" />
        <x-site.emblem class="hidden lg:block absolute left-[6%] bottom-[12%] w-4 h-4 text-white/15" />

        <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-14 sm:pt-18 lg:pt-20 pb-16 sm:pb-20 lg:pb-24 grid lg:grid-cols-[1.05fr_1fr] gap-10 lg:gap-14 items-center">

            {{-- Konten teks — selalu tampil lebih dulu (mobile & desktop), visual jadi pendukung --}}
            <div class="text-center lg:text-left order-1" style="animation: fadeInUp .8s cubic-bezier(.22,.61,.36,1) both;">
                <span class="relative inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs tracking-wide uppercase bg-white/10 text-[var(--color-gold-soft)] border border-white/15 backdrop-blur-sm overflow-hidden">
                    <span class="absolute inset-0 rounded-full bg-[var(--color-gold)]/10 animate-[pulseGlow_2.6s_ease-in-out_infinite]"></span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="relative w-3 h-3 shrink-0"><path fill-rule="evenodd" d="M10 1.5c.28 0 .55.15.7.4l2.02 3.5 4.02.85a.8.8 0 01.44 1.33l-2.78 2.96.62 4.07a.8.8 0 01-1.15.84L10 13.6l-3.87 1.85a.8.8 0 01-1.15-.84l.62-4.07L2.82 7.58a.8.8 0 01.44-1.33l4.02-.85 2.02-3.5a.8.8 0 01.7-.4z" clip-rule="evenodd"/></svg>
                    <span class="relative">Terdaftar Resmi Kemenag RI</span>
                </span>

                <h1 class="font-display text-[clamp(2.35rem,4.6vw+1.1rem,4.2rem)] leading-[1.08] tracking-tight text-white mt-5 sm:mt-6 text-balance">
                    {{ $settings['site_tagline'] ?? 'Wujudkan Perjalanan Ibadah yang Nyaman & Berkah' }}
                </h1>

                <div class="flex items-center justify-center lg:justify-start gap-3 mt-4">
                    <span class="h-px w-10 bg-gradient-to-r from-[var(--color-gold-soft)]/80 to-[var(--color-gold-soft)]/0"></span>
                    <x-site.emblem class="w-3 h-3 text-[var(--color-gold-soft)]" />
                </div>

                <p class="text-white/70 mt-5 max-w-lg mx-auto lg:mx-0 text-[15px] sm:text-base leading-relaxed">
                    {{ $siteName }} menghadirkan perjalanan umroh &amp; haji yang tenang, terbimbing, dan transparan —
                    dari persiapan dokumen hingga pulang ke rumah.
                </p>

                <div class="mt-7 sm:mt-9 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3.5">
                    <a href="#paket" class="hero-cta-primary group w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] font-semibold text-sm hover:brightness-105 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-[var(--color-gold)]/30 active:translate-y-0 active:scale-[0.98] transition-all duration-300">
                        Lihat Paket Umroh &amp; Haji
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 transition-transform duration-300 group-hover:translate-x-1">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                    <a href="#kontak" class="hero-cta-secondary w-full sm:w-auto px-8 py-4 rounded-full border border-white/25 bg-white/[0.03] text-white text-sm font-medium hover:bg-white/10 hover:border-white/40 hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300">
                        Konsultasi Gratis
                    </a>
                </div>

                {{-- Strip kepercayaan singkat — memperkuat kesan resmi & premium sebelum angka statistik --}}
                <div class="mt-6 flex flex-wrap items-center justify-center lg:justify-start gap-x-5 gap-y-2 text-[11px] sm:text-xs text-white/50 tracking-wide">
                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="var(--color-gold-soft)" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Izin Resmi PPIU/PIHK</span>
                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="var(--color-gold-soft)" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Pembimbing Bersertifikat</span>
                    <span class="inline-flex items-center gap-1.5"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="var(--color-gold-soft)" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>Dana Talangan Aman</span>
                </div>

                <div class="mt-6 sm:mt-8 grid grid-cols-3 gap-3 sm:gap-6 max-w-lg mx-auto lg:mx-0 rounded-2xl border border-white/10 bg-white/[0.04] backdrop-blur-sm px-4 sm:px-6 py-5 divide-x divide-white/10 hover:border-white/20 transition-colors duration-300">
                    <div class="text-center lg:text-left px-1 transition-transform duration-300 hover:-translate-y-0.5">
                        <p class="font-display text-2xl sm:text-3xl text-white">{{ $packages->count() }}+</p>
                        <p class="text-white/55 text-[11px] sm:text-xs mt-1 tracking-wide">Paket Tersedia</p>
                    </div>
                    <div class="text-center lg:text-left px-1 transition-transform duration-300 hover:-translate-y-0.5">
                        <p class="font-display text-2xl sm:text-3xl text-white">{{ \App\Models\Booking::count() }}+</p>
                        <p class="text-white/55 text-[11px] sm:text-xs mt-1 tracking-wide">Jamaah Terlayani</p>
                    </div>
                    <div class="text-center lg:text-left px-1 transition-transform duration-300 hover:-translate-y-0.5">
                        @php $avgRating = \App\Models\Testimonial::where('is_published', true)->avg('rating'); @endphp
                        <p class="font-display text-2xl sm:text-3xl text-white">{{ $avgRating ? number_format($avgRating, 1) : '5.0' }}/5</p>
                        <p class="text-white/55 text-[11px] sm:text-xs mt-1 tracking-wide">Rating Jamaah</p>
                    </div>
                </div>
            </div>

            @php
                $heroImage = $galleryItems->firstWhere('is_featured', true) ?? $galleryItems->first();
            @endphp
            {{-- Visual dekoratif — pendukung, tampil setelah pesan utama tersampaikan --}}
            <div class="order-2 flex justify-center" style="animation: fadeInUp 1s .15s cubic-bezier(.22,.61,.36,1) both;">
                <div class="relative w-full max-w-[300px] sm:max-w-[340px] aspect-[4/4.7]">
                    <div class="absolute -inset-3 rounded-[var(--radius-arch)] bg-gradient-to-br from-[var(--color-gold)]/25 via-white/5 to-transparent blur-md"></div>
                    <div class="absolute inset-0 rounded-[var(--radius-arch)] bg-white/5 border border-white/10 animate-[floatSlow_7s_ease-in-out_infinite]"></div>

                    {{-- Bingkai sudut ornamen emas — sentuhan "mewah" khas undangan/pigura klasik --}}
                    <svg class="absolute -top-2 -left-2 w-9 h-9 sm:w-11 sm:h-11 text-[var(--color-gold-soft)]/70 pointer-events-none z-10" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                        <path d="M2 20V8a6 6 0 016-6h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        <circle cx="2" cy="2" r="1.6" fill="currentColor"/>
                    </svg>
                    <svg class="absolute -bottom-2 -right-2 w-9 h-9 sm:w-11 sm:h-11 text-[var(--color-gold-soft)]/70 pointer-events-none z-10 rotate-180" viewBox="0 0 44 44" fill="none" aria-hidden="true">
                        <path d="M2 20V8a6 6 0 016-6h12" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                        <circle cx="2" cy="2" r="1.6" fill="currentColor"/>
                    </svg>

                    <div class="absolute inset-3 sm:inset-4 rounded-[var(--radius-arch)] overflow-hidden border border-[var(--color-gold)]/30 shadow-2xl shadow-black/30">
                        @if ($heroImage && $heroImage->file_path)
                            <img src="{{ asset('storage/' . $heroImage->file_path) }}" alt="{{ $heroImage->title }}"
                                class="absolute inset-0 w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary-dark)]/70 via-transparent to-[var(--color-primary-dark)]/10"></div>
                        @else
                            {{-- Fallback kalau belum ada foto di galeri --}}
                            <div class="absolute inset-0 bg-gradient-to-b from-[var(--color-gold)]/15 to-transparent flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="var(--color-gold)" class="w-20 h-20 opacity-70">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3.75c-4.5 0-7.5 3.75-7.5 8.25v6.75a1.5 1.5 0 001.5 1.5h12a1.5 1.5 0 001.5-1.5V12c0-4.5-3-8.25-7.5-8.25z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 20.25v-4.5a3 3 0 116 0v4.5" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <div class="absolute -left-3 sm:-left-8 top-6 sm:top-10 bg-[var(--color-surface)]/95 backdrop-blur-sm rounded-2xl shadow-xl shadow-black/20 ring-1 ring-black/5 px-3.5 sm:px-4 py-2.5 sm:py-3 flex items-center gap-2.5 sm:gap-3 animate-[float_5s_ease-in-out_infinite]">
                        <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[var(--color-success)]/10 text-[var(--color-success)] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-4.5 sm:h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                        </span>
                        <div>
                            <p class="text-[11px] sm:text-xs font-semibold text-[var(--color-ink)]">Legalitas Resmi</p>
                            <p class="text-[10px] sm:text-[11px] text-[var(--color-ink-soft)]">Kemenag RI</p>
                        </div>
                    </div>

                    <div class="absolute -right-3 sm:-right-8 bottom-10 sm:bottom-16 bg-[var(--color-surface)]/95 backdrop-blur-sm rounded-2xl shadow-xl shadow-black/20 ring-1 ring-black/5 px-3.5 sm:px-4 py-2.5 sm:py-3 flex items-center gap-2.5 sm:gap-3 animate-[float_5s_.6s_ease-in-out_infinite]">
                        <span class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-[var(--color-gold)]/20 text-[var(--color-warning)] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20" class="w-4 h-4 sm:w-4.5 sm:h-4.5"><path fill-rule="evenodd" d="M10 1.5c.28 0 .55.15.7.4l2.02 3.5 4.02.85a.8.8 0 01.44 1.33l-2.78 2.96.62 4.07a.8.8 0 01-1.15.84L10 13.6l-3.87 1.85a.8.8 0 01-1.15-.84l.62-4.07L2.82 7.58a.8.8 0 01.44-1.33l4.02-.85 2.02-3.5a.8.8 0 01.7-.4z" clip-rule="evenodd"/></svg>
                        </span>
                        <div>
                            <p class="text-[11px] sm:text-xs font-semibold text-[var(--color-ink)]">{{ $avgRating ? number_format($avgRating, 1) : '5.0' }}/5 Rating</p>
                            <p class="text-[10px] sm:text-[11px] text-[var(--color-ink-soft)]">Dari jamaah kami</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Isyarat scroll halus — mengundang eksplorasi tanpa mengganggu --}}
        <a href="#keunggulan" aria-label="Gulir untuk melihat selengkapnya"
           class="hidden lg:flex absolute left-1/2 -translate-x-1/2 bottom-16 flex-col items-center gap-1.5 text-white/40 hover:text-white/70 transition-colors duration-300">
            <span class="text-[10px] uppercase tracking-[0.2em]">Scroll</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="scroll-cue-icon w-4 h-4 animate-[scrollCue_1.8s_ease-in-out_infinite]">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </a>

        {{-- Transisi bermotif kubah — identitas khas, bukan wave generik --}}
        <x-site.divider variant="arch" fill="var(--color-paper)" class="h-9 sm:h-12 lg:h-14 drop-shadow-[0_-6px_14px_rgba(15,23,42,0.12)]" />
    </section>

    {{-- ============ KEUNGGULAN ============ --}}
    <section id="keunggulan" class="relative overflow-hidden max-w-7xl mx-auto px-5 sm:px-8 pt-10 sm:pt-14 pb-14 sm:pb-20">
        <div class="absolute inset-0 -z-10 geo-lattice opacity-[0.04]"></div>

        {{-- Outline circle tipis, memberi kedalaman tanpa ramai --}}
        <div class="absolute -right-24 top-6 w-72 h-72 rounded-full border border-[var(--color-primary)]/10 -z-10 pointer-events-none hidden sm:block" aria-hidden="true"></div>
        <div class="absolute -right-8 top-24 w-36 h-36 rounded-full border border-[var(--color-gold)]/15 -z-10 pointer-events-none hidden sm:block" aria-hidden="true"></div>

        <div class="text-center max-w-xl mx-auto mb-10 sm:mb-12 reveal">
            <div class="flex items-center justify-center gap-3 mb-3">
                <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
                <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
                <span class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold)]/70"></span>
            </div>
            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Kenapa Memilih Kami</span>
            <h2 class="font-display text-2xl sm:text-3xl mt-1.5">Ibadah Nyaman, Hati Tenang</h2>
        </div>

        {{-- Jalur penghubung — mengubah grid rata jadi narasi berurutan, bukan tumpukan card --}}
        <div class="relative">
            <svg class="hidden lg:block absolute top-[38px] left-[12.5%] right-[12.5%] w-[75%] h-8 -z-10 opacity-25 pointer-events-none" viewBox="0 0 800 40" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0 6 Q 100 34 267 20 T 533 20 T 800 6" fill="none" stroke="var(--color-gold)" stroke-width="1.5" stroke-dasharray="1 10" stroke-linecap="round"/>
            </svg>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 lg:gap-6">
                @php
                    $features = [
                        ['title' => 'Pembimbing Berpengalaman', 'desc' => 'Ustadz pembimbing mendampingi penuh dari manasik hingga di tanah suci.', 'icon' => 'M4.5 12.75l6 6 9-13.5'],
                        ['title' => 'Harga Transparan', 'desc' => 'Rincian biaya jelas sejak awal, tanpa tambahan tersembunyi.', 'icon' => 'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33'],
                        ['title' => 'Hotel Dekat Masjid', 'desc' => 'Akomodasi terpilih dalam jangkauan berjalan kaki ke Masjidil Haram/Nabawi.', 'icon' => 'M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21'],
                        ['title' => 'Layanan 24 Jam', 'desc' => 'Tim kami siap membantu jamaah kapan pun selama perjalanan berlangsung.', 'icon' => 'M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ];
                @endphp

                @foreach ($features as $i => $f)
                    <div class="reveal {{ $i % 2 === 1 ? 'lg:mt-10' : '' }}" style="transition-delay:{{ $i * 70 }}ms">
                        <span class="hidden lg:flex relative z-10 w-9 h-9 rounded-full bg-[var(--color-paper)] border-2 border-[var(--color-gold)] items-center justify-center font-display text-xs text-[var(--color-primary)] mb-4 mx-auto shadow-sm shadow-[var(--color-gold)]/30">0{{ $i + 1 }}</span>
                        <div class="group arch-accent relative bg-[var(--color-surface)] rounded-[var(--radius-card)] p-5 border border-[var(--color-line)] overflow-hidden hover:-translate-y-1.5 hover:shadow-xl hover:shadow-[var(--color-primary)]/5 hover:border-[var(--color-primary)]/20 transition-all duration-300 ease-premium">
                            <span class="absolute top-0 left-0 h-[3px] w-0 bg-gradient-to-r from-[var(--color-gold)] to-[var(--color-primary)] group-hover:w-full transition-all duration-500 ease-premium"></span>
                            <span class="lg:hidden absolute top-4 right-5 font-display text-3xl text-[var(--color-primary)]/[0.06] select-none">0{{ $i + 1 }}</span>
                            <div class="relative w-10 h-10 rounded-full bg-[var(--color-primary)]/10 ring-1 ring-[var(--color-gold)]/20 flex items-center justify-center text-[var(--color-primary)] mb-3 group-hover:bg-[var(--color-primary)] group-hover:ring-[var(--color-primary)]/40 group-hover:text-white transition-all duration-300 ease-premium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['icon'] }}" />
                                </svg>
                            </div>
                            <h3 class="relative font-display text-lg">{{ $f['title'] }}</h3>
                            <p class="relative text-sm text-[var(--color-ink-soft)] mt-1.5 leading-relaxed">{{ $f['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ ALUR PERJALANAN — storytelling langkah demi langkah, section baru ============ --}}
    <section id="alur" class="relative overflow-hidden bg-[var(--color-primary-dark)] grain-overlay">
        <div class="absolute inset-0 geo-lattice-light opacity-[0.045]" aria-hidden="true"></div>
        <div class="absolute left-1/2 -translate-x-1/2 -top-20 w-[32rem] h-[32rem] rounded-full bg-[var(--color-gold)]/[0.07] blur-[110px] pointer-events-none" aria-hidden="true"></div>
        <x-site.emblem class="hidden lg:block absolute right-10 bottom-10 w-10 h-10 text-white/[0.06]" />

        <div class="relative max-w-6xl mx-auto px-5 sm:px-8 py-16 sm:py-24">
            <div class="text-center max-w-xl mx-auto mb-12 sm:mb-16 reveal">
                <div class="flex items-center justify-center gap-3 mb-3">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
                    <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold)]/70"></span>
                </div>
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-gold-soft)]">Dari Niat Hingga Pulang</span>
                <h2 class="font-display text-2xl sm:text-3xl mt-1.5 text-white text-balance">Alur Perjalanan Bersama Kami</h2>
                <p class="text-sm text-white/60 mt-3">Setiap tahap kami dampingi, agar Anda cukup fokus pada niat dan ibadah.</p>
            </div>

            <div class="relative grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-4">
                {{-- Garis penghubung horizontal khas, senada dengan section Keunggulan --}}
                <svg class="hidden lg:block absolute top-[26px] left-[10%] right-[10%] w-[80%] h-4 -z-0 opacity-30 pointer-events-none" viewBox="0 0 800 20" preserveAspectRatio="none" aria-hidden="true">
                    <line x1="0" y1="10" x2="800" y2="10" stroke="var(--color-gold-soft)" stroke-width="1.2" stroke-dasharray="1 10" stroke-linecap="round"/>
                </svg>

                @php
                    $journey = [
                        ['title' => 'Konsultasi', 'desc' => 'Diskusikan kebutuhan &amp; anggaran bersama tim kami, gratis tanpa komitmen.'],
                        ['title' => 'Pendaftaran', 'desc' => 'Isi data jamaah &amp; unggah dokumen langsung dari dashboard online.'],
                        ['title' => 'Manasik', 'desc' => 'Ikuti pembekalan bersama ustadz pembimbing sebelum keberangkatan.'],
                        ['title' => 'Keberangkatan', 'desc' => 'Terbang bersama rombongan dengan pendampingan penuh selama ibadah.'],
                        ['title' => 'Pulang & Kenangan', 'desc' => 'Tiba kembali dengan hati tenang &amp; dokumentasi perjalanan lengkap.'],
                    ];
                @endphp

                @foreach ($journey as $i => $step)
                    <div class="reveal relative z-10 text-center lg:text-left" style="transition-delay:{{ $i * 80 }}ms">
                        <span class="relative inline-flex w-12 h-12 rounded-full items-center justify-center bg-[var(--color-primary-dark)] border-2 border-[var(--color-gold)] font-display text-base text-[var(--color-gold-soft)] mb-4 shadow-lg shadow-black/30">
                            0{{ $i + 1 }}
                        </span>
                        <h3 class="font-display text-base sm:text-lg text-white">{{ $step['title'] }}</h3>
                        <p class="text-xs sm:text-[13px] text-white/55 mt-1.5 leading-relaxed max-w-[15rem] mx-auto lg:mx-0">{!! $step['desc'] !!}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ============ JEMBATAN VISUAL — menyatukan Keunggulan & Paket lewat kartu overlap, bukan wave ============ --}}
    <div class="relative z-20 max-w-4xl mx-auto px-5 sm:px-8 -mb-12 sm:-mb-16">
        <div class="reveal relative overflow-hidden grain-overlay bg-[var(--color-primary)] rounded-[var(--radius-card)] px-6 sm:px-10 py-6 sm:py-7 shadow-2xl shadow-[var(--color-primary)]/25 flex flex-col sm:flex-row items-center gap-4 sm:gap-7 text-center sm:text-left">
            <div class="absolute inset-0 geo-lattice-light opacity-[0.05] pointer-events-none"></div>
            <x-site.emblem class="relative shrink-0 w-8 h-8 sm:w-9 sm:h-9 text-[var(--color-gold-soft)]" />
            <p class="relative font-display text-base sm:text-lg text-white leading-snug flex-1">
                &ldquo;Setiap perjalanan kami rancang seperti untuk keluarga sendiri &mdash; nyaman dari niat hingga pulang.&rdquo;
            </p>
            <a href="#paket" class="relative shrink-0 inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] text-xs sm:text-sm font-semibold hover:brightness-105 hover:-translate-y-0.5 transition-all duration-300">
                Lihat Paket
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
            </a>
        </div>
    </div>

    {{-- ============ PAKET (DATA ASLI DARI DATABASE) — focal point halaman ============ --}}
    <section id="paket" x-data="{ activeCat: 'all' }" class="relative overflow-hidden bg-[var(--color-surface)]">
        <div class="absolute -right-24 -bottom-24 w-80 h-80 rounded-full bg-[var(--color-gold)]/[0.05] blur-3xl -z-10 pointer-events-none" aria-hidden="true"></div>
        <div class="absolute inset-0 -z-10 geo-lattice opacity-[0.025]" aria-hidden="true"></div>
        <x-site.emblem class="hidden lg:block absolute left-8 top-10 w-8 h-8 text-[var(--color-primary)]/[0.05]" />

        <div class="max-w-7xl mx-auto px-5 sm:px-8 pt-20 sm:pt-24 pb-14 sm:pb-20">
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-9 sm:mb-10 reveal">
                <div class="text-center sm:text-left">
                    <div class="flex items-center justify-center sm:justify-start gap-3 mb-3">
                        <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
                        <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
                        <span class="hidden sm:block h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold)]/70"></span>
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Pilihan Perjalanan</span>
                    <h2 class="font-display text-2xl sm:text-3xl mt-1.5">Paket Umroh &amp; Haji</h2>
                    <p class="text-sm text-[var(--color-ink-soft)] mt-2 max-w-md">Paket resmi kami yang sedang berjalan. Hubungi tim untuk jadwal dan ketersediaan kursi.</p>
                </div>

                @if ($packageCategories->isNotEmpty())
                    {{-- Filter kategori — pill, disaring langsung di sisi klien --}}
                    <div class="flex flex-wrap justify-center sm:justify-end gap-2 shrink-0">
                        <button type="button" @click="activeCat = 'all'"
                                class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all duration-200"
                                :class="activeCat === 'all' ? 'bg-[var(--color-primary)] text-white border-[var(--color-primary)]' : 'border-[var(--color-line)] text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)]'">
                            Semua
                        </button>
                        @foreach ($packageCategories as $cat)
                            <button type="button" @click="activeCat = '{{ $cat->slug }}'"
                                    class="px-4 py-1.5 rounded-full text-xs font-medium border transition-all duration-200"
                                    :class="activeCat === '{{ $cat->slug }}' ? 'bg-[var(--color-primary)] text-white border-[var(--color-primary)]' : 'border-[var(--color-line)] text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)]'">
                                {{ $cat->name }}
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if ($packages->isEmpty())
                <div class="text-center py-16 border border-dashed border-[var(--color-line)] rounded-[var(--radius-card)] reveal">
                    <p class="text-[var(--color-ink-soft)] text-sm">Belum ada paket yang dipublikasikan saat ini. Silakan cek kembali nanti.</p>
                </div>
            @else
                {{-- Satu paket unggulan sebagai focal point, sisanya grid asimetris di bawahnya --}}
                @php $featuredPackage = $packages->first(); $restPackages = $packages->skip(1); @endphp

                <div x-show="activeCat === 'all' || activeCat === '{{ $featuredPackage->category->slug ?? '' }}'" x-cloak class="reveal mb-6">
                    <x-site.package-card :package="$featuredPackage" :featured="true" />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5 lg:gap-6">
                    @foreach ($restPackages as $i => $p)
                        <div x-show="activeCat === 'all' || activeCat === '{{ $p->category->slug ?? '' }}'" x-cloak
                             style="transition-delay:{{ ($i % 3) * 80 }}ms">
                            <x-site.package-card :package="$p" />
                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-9 reveal">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-[var(--color-primary)] hover:underline">
                        Lihat Semua Paket
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </a>
                </div>
            @endif
        </div>

        <x-site.divider variant="diagonal" fill="var(--color-paper)" class="h-8 sm:h-10" />
    </section>

    {{-- ============ ARTIKEL (DATA ASLI DARI DATABASE) ============ --}}
    @if ($articles->isNotEmpty())
        <section id="artikel" class="relative overflow-hidden max-w-7xl mx-auto px-5 sm:px-8 py-14 sm:py-20">
            {{-- Garis lengkung tipis penghubung antar kolom, memperkuat alur baca --}}
            <svg class="absolute inset-0 w-full h-full -z-10 opacity-[0.06] pointer-events-none hidden lg:block" viewBox="0 0 1200 500" preserveAspectRatio="none" aria-hidden="true">
                <path d="M600 0 C 555 160, 645 340, 600 500" fill="none" stroke="var(--color-primary)" stroke-width="1"/>
            </svg>

            <div class="grid lg:grid-cols-2 gap-8 lg:gap-12 items-start">

                {{-- Artikel utama --}}
                @php $featured = $articles->first(); @endphp
                <a href="{{ route('articles.show', $featured) }}" class="reveal group relative block rounded-[var(--radius-card)] overflow-hidden border border-[var(--color-line)] h-72 sm:h-[26rem] transition-all duration-300 ease-premium transform-gpu hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-[var(--color-primary)]/15 active:scale-[0.985] active:duration-150">
                    @if ($featured->thumbnail)
                        <img src="{{ asset('storage/' . $featured->thumbnail) }}" alt="{{ $featured->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
                    @else
                        <div class="absolute inset-0 bg-[var(--color-primary)]"></div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary-dark)]/85 via-[var(--color-primary-dark)]/20 to-transparent transition-opacity duration-300 group-hover:opacity-95"></div>

                    {{-- Efek shine tipis --}}
                    <div class="pointer-events-none absolute inset-0 overflow-hidden">
                        <div class="absolute -left-1/2 -top-1/2 h-[200%] w-1/3 rotate-12 bg-gradient-to-r from-transparent via-white/15 to-transparent opacity-0 -translate-x-[150%] group-hover:opacity-100 group-hover:translate-x-[280%] transition-all duration-700 ease-out"></div>
                    </div>

                    <div class="absolute bottom-0 p-6 sm:p-7">
                        <span class="text-[11px] uppercase tracking-wide text-[var(--color-gold-soft)]">Artikel Terbaru</span>
                        <h3 class="font-display text-xl sm:text-2xl text-white mt-2 leading-snug transition-colors duration-300 group-hover:text-[var(--color-gold-soft)]">{{ $featured->title }}</h3>
                        <span class="inline-flex items-center gap-1.5 text-sm text-white/80 mt-3 group-hover:text-white group-hover:gap-2.5 transition-all duration-300 ease-premium">
                            Baca selengkapnya
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-300 ease-premium group-hover:translate-x-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                        </span>
                    </div>
                </a>

                {{-- Daftar artikel lain --}}
                <div class="reveal">
                    <div class="flex items-end justify-between mb-5">
                        <div>
                            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Wawasan &amp; Panduan</span>
                            <h2 class="font-display text-2xl sm:text-3xl mt-1.5">Artikel</h2>
                            <p class="text-sm text-[var(--color-ink-soft)] mt-2">Tips persiapan, panduan ibadah, dan cerita seputar perjalanan Umroh &amp; Haji.</p>
                        </div>
                        <a href="{{ route('articles.index') }}" class="hidden sm:inline text-sm font-medium text-[var(--color-primary)] hover:underline shrink-0 ml-4">Lainnya &rarr;</a>
                    </div>

                    <div class="space-y-3">
                        @foreach ($articles->skip(1)->take(2) as $article)
                            <a href="{{ route('articles.show', $article) }}"
                            class="group relative flex items-stretch rounded-[1.75rem] border border-[var(--color-line)] overflow-hidden bg-[var(--color-surface)] transition-all duration-300 ease-premium transform-gpu hover:-translate-y-1 hover:shadow-lg hover:shadow-[var(--color-primary)]/10 hover:border-[var(--color-primary)]/30 active:scale-[0.985] active:duration-150">

                                {{-- Thumbnail --}}
                                <div class="w-28 sm:w-32 shrink-0 relative bg-[var(--color-primary)]/8 overflow-hidden">
                                    @if ($article->thumbnail)
                                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}"
                                            loading="lazy" onload="this.classList.add('is-loaded')"
                                            class="fade-img absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                                    @else
                                        <div class="absolute inset-0 flex items-center justify-center text-[var(--color-primary)]/25">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.4" stroke="currentColor" class="w-9 h-9">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 4.5h18A1.5 1.5 0 0122.5 6v12a1.5 1.5 0 01-1.5 1.5H3A1.5 1.5 0 011.5 18V6A1.5 1.5 0 013 4.5zM9.75 9.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>

                                {{-- Konten teks --}}
                                <div class="flex-1 min-w-0 p-4 sm:p-5 flex flex-col justify-between bg-[var(--color-surface)]">
                                    <div>
                                        <h4 class="font-display text-base leading-snug transition-colors duration-300 group-hover:text-[var(--color-primary)] line-clamp-1">
                                            {{ $article->title }}
                                        </h4>
                                        <p class="text-xs text-[var(--color-ink-soft)] mt-1.5 line-clamp-2">
                                            {{ Str::limit(strip_tags($article->content), 90) }}
                                        </p>
                                    </div>
                                    <span class="self-end inline-flex items-center gap-1 text-xs font-medium text-[var(--color-primary)] mt-2 shrink-0 transition-all duration-300 ease-premium group-hover:gap-1.5">
                                        Baca selengkapnya
                                        <span class="transition-transform duration-300 ease-premium group-hover:translate-x-0.5">&rarr;</span>
                                    </span>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <a href="{{ route('articles.index') }}" class="sm:hidden block text-center mt-5 text-sm font-medium text-[var(--color-primary)] hover:underline">Lihat Semua Artikel &rarr;</a>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ GALERI — marquee premium, dua baris berlawanan arah ============ --}}
    @if ($galleryItems->isNotEmpty())
        @php
            $half = (int) ceil($galleryItems->count() / 2);
            $rowOne = $galleryItems->slice(0, $half)->values();
            $rowTwo = $galleryItems->slice($half)->values();
            if ($rowTwo->isEmpty()) { $rowTwo = $rowOne; }
        @endphp
        <section id="galeri" class="relative grain-overlay bg-[var(--color-primary-dark)] py-14 sm:py-20 overflow-hidden">
            {{-- Transisi terang → gelap lewat potongan diagonal tajam, memberi jeda ritme dari wave sebelumnya --}}
            <x-site.divider variant="diagonal" fill="var(--color-paper)" :flip="true" class="h-10 sm:h-14 lg:h-16" />
            <div class="absolute inset-0 geo-lattice-light opacity-[0.035] pointer-events-none"></div>

            <div class="max-w-7xl mx-auto px-5 sm:px-8 text-center mb-9 sm:mb-10 reveal">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold-soft)]/70"></span>
                    <x-site.emblem class="w-3 h-3 text-[var(--color-gold-soft)]" />
                    <span class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold-soft)]/70"></span>
                </div>
                <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-gold-soft)]">Momen Jamaah</span>
                <h2 class="font-display text-2xl sm:text-3xl mt-2 text-white">Galeri Kami</h2>
                <p class="text-sm text-white/60 mt-3 max-w-md mx-auto">Momen perjalanan jamaah kami di Tanah Suci. Ketuk foto untuk melihat lebih besar.</p>
            </div>

            <div class="space-y-4">
                {{-- Baris 1 : bergerak ke kiri --}}
                <div class="marquee-row overflow-hidden" style="mask-image:linear-gradient(to right, transparent, black 6%, black 94%, transparent); -webkit-mask-image:linear-gradient(to right, transparent, black 6%, black 94%, transparent);">
                    <div class="marquee-track gap-4 px-2">
                        @foreach ([1, 2] as $dup)
                            @foreach ($rowOne as $item)
                                <button type="button"
                                        @click="openLightbox({{ $item->id }})"
                                        class="group relative w-56 h-40 sm:w-72 sm:h-48 shrink-0 rounded-2xl overflow-hidden border border-white/10 transition-all duration-300 ease-premium hover:-translate-y-1 hover:border-[var(--color-gold)]/40 hover:shadow-xl hover:shadow-black/30 active:scale-[0.98]">
                                    @if ($item->file_path)
                                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
                                    @else
                                        <div class="w-full h-full bg-white/5"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-xs font-medium truncate translate-y-1 group-hover:translate-y-0 transition-transform duration-300 ease-premium">{{ $item->title }}</span>
                                    </div>
                                </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>

                {{-- Baris 2 : bergerak ke kanan --}}
                <div class="marquee-row overflow-hidden" style="mask-image:linear-gradient(to right, transparent, black 6%, black 94%, transparent); -webkit-mask-image:linear-gradient(to right, transparent, black 6%, black 94%, transparent);">
                    <div class="marquee-track marquee-track-reverse gap-4 px-2">
                        @foreach ([1, 2] as $dup)
                            @foreach ($rowTwo as $item)
                                <button type="button"
                                        @click="openLightbox({{ $item->id }})"
                                        class="group relative w-56 h-40 sm:w-72 sm:h-48 shrink-0 rounded-2xl overflow-hidden border border-white/10 transition-all duration-300 ease-premium hover:-translate-y-1 hover:border-[var(--color-gold)]/40 hover:shadow-xl hover:shadow-black/30 active:scale-[0.98]">
                                    @if ($item->file_path)
                                        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
                                    @else
                                        <div class="w-full h-full bg-white/5"></div>
                                    @endif
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
                                        <span class="text-white text-xs font-medium truncate translate-y-1 group-hover:translate-y-0 transition-transform duration-300 ease-premium">{{ $item->title }}</span>
                                    </div>
                                </button>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="{{ route('gallery.index') }}" class="text-sm font-medium text-[var(--color-gold-soft)] hover:underline">Lihat Semua Galeri &rarr;</a>
            </div>

            <x-site.divider variant="angle" fill="var(--color-surface)" class="h-9 sm:h-12" />
        </section>

        {{-- Lightbox --}}
        <div x-show="lightboxOpen" x-cloak x-transition.opacity.duration.250ms @click.self="lightboxOpen = false"
             class="fixed inset-0 z-[60] bg-black/90 backdrop-blur-sm flex items-center justify-center p-5">
            <button @click="lightboxOpen = false" class="absolute top-5 right-5 text-white/70 hover:text-white p-2 z-10" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>

            <button x-show="lightboxItems.length > 1" x-cloak @click="prevLightbox()" aria-label="Foto sebelumnya"
                    class="absolute left-2 sm:left-5 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-2 sm:p-3 rounded-full hover:bg-white/10 active:scale-90 transition-all duration-200 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            </button>
            <button x-show="lightboxItems.length > 1" x-cloak @click="nextLightbox()" aria-label="Foto berikutnya"
                    class="absolute right-2 sm:right-5 top-1/2 -translate-y-1/2 text-white/70 hover:text-white p-2 sm:p-3 rounded-full hover:bg-white/10 active:scale-90 transition-all duration-200 z-10">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </button>

            <div x-show="lightboxOpen"
                 x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                 class="max-w-3xl w-full">
                <img :src="lightboxImg" :alt="lightboxTitle" class="w-full max-h-[75vh] object-contain rounded-xl">
                <div class="flex items-center justify-between gap-4 mt-4">
                    <p x-text="lightboxTitle" class="text-white/80 text-sm truncate"></p>
                    <p x-show="lightboxItems.length > 1" x-cloak class="text-white/40 text-xs shrink-0" x-text="(lightboxIndex + 1) + ' / ' + lightboxItems.length"></p>
                </div>
            </div>
        </div>
    @endif

    {{-- ============ TESTIMONI — carousel horizontal, pola berbeda dari grid section lain ============ --}}
    @if ($testimonials->isNotEmpty())
        <section id="testimoni" x-data="{ track: null }" class="relative overflow-hidden max-w-7xl mx-auto px-5 sm:px-8 py-14 sm:py-20">
            {{-- Radial glow lembut & outline circle, menegaskan kedalaman tanpa ramai --}}
            <div class="absolute left-1/2 -translate-x-1/2 -top-10 w-[28rem] h-[28rem] rounded-full bg-[var(--color-primary)]/[0.04] blur-3xl -z-10 pointer-events-none" aria-hidden="true"></div>
            <div class="absolute -left-20 bottom-6 w-56 h-56 rounded-full border border-[var(--color-primary)]/10 -z-10 pointer-events-none hidden sm:block" aria-hidden="true"></div>
            <div class="absolute inset-x-0 top-1/3 h-64 geo-lattice opacity-[0.025] -z-10 pointer-events-none" aria-hidden="true"></div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="hidden sm:block absolute right-2 top-2 w-28 h-28 lg:w-36 lg:h-36 text-[var(--color-primary)]/[0.04] -z-10 pointer-events-none" aria-hidden="true"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-2.2 1.8-4 4-4V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"/></svg>

            {{-- Header split — judul di kiri, kontrol navigasi di kanan (bukan judul tengah seperti section lain) --}}
            <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 mb-8 sm:mb-10 reveal">
                <div class="text-center sm:text-left">
                    <div class="flex items-center justify-center sm:justify-start gap-3 mb-3">
                        <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
                        <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
                    </div>
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Cerita Jamaah</span>
                    <h2 class="font-display text-2xl sm:text-3xl mt-1.5">Apa Kata Mereka</h2>
                    @php $avgRatingT = \App\Models\Testimonial::where('is_published', true)->avg('rating'); @endphp
                    <div class="inline-flex items-center gap-2 mt-3 px-3.5 py-1.5 rounded-full bg-[var(--color-gold)]/10 border border-[var(--color-gold)]/25">
                        <div class="flex text-[var(--color-gold)]">
                            @for ($k = 0; $k < 5; $k++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $k < round($avgRatingT ?: 5) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1" class="w-3.5 h-3.5"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.299.921-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.783.57-1.837-.197-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.287-3.958z"/></svg>
                            @endfor
                        </div>
                        <span class="text-xs font-semibold text-[var(--color-ink)]">{{ $avgRatingT ? number_format($avgRatingT, 1) : '5.0' }}/5</span>
                        <span class="text-xs text-[var(--color-ink-soft)]">&middot; {{ $testimonials->count() }}+ ulasan jamaah</span>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-2 shrink-0">
                    <button type="button" @click="track.scrollBy({ left: -360, behavior: 'smooth' })"
                            class="w-10 h-10 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)] transition-colors" aria-label="Sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                    </button>
                    <button type="button" @click="track.scrollBy({ left: 360, behavior: 'smooth' })"
                            class="w-10 h-10 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)] transition-colors" aria-label="Berikutnya">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                    </button>
                </div>
            </div>

            <div x-ref="track" x-init="track = $refs.track" class="reveal no-scrollbar flex gap-5 overflow-x-auto pb-3 -mx-5 px-5 sm:mx-0 sm:px-0 snap-x snap-mandatory scroll-smooth" style="scrollbar-width:none;">
                @foreach ($testimonials as $i => $t)
                    <div class="group relative bg-[var(--color-surface)] rounded-[var(--radius-card)] p-6 border border-[var(--color-line)] overflow-hidden hover:shadow-xl hover:shadow-[var(--color-primary)]/5 hover:-translate-y-1.5 hover:border-[var(--color-primary)]/20 transition-all duration-300 ease-premium snap-start shrink-0 w-[85%] sm:w-80">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32" fill="currentColor" class="absolute -top-1 right-4 w-12 h-12 text-[var(--color-primary)]/[0.06]"><path d="M10 8c-3.3 0-6 2.7-6 6v10h10V14H8c0-2.2 1.8-4 4-4V8zm14 0c-3.3 0-6 2.7-6 6v10h10V14h-6c0-2.2 1.8-4 4-4V8z"/></svg>
                        <div class="relative flex gap-1 text-[var(--color-gold)] mb-4">
                            @for ($j = 0; $j < 5; $j++)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="{{ $j < $t->rating ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1" class="w-4 h-4"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.958a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.368 2.447a1 1 0 00-.363 1.118l1.287 3.957c.299.921-.755 1.688-1.538 1.118l-3.367-2.447a1 1 0 00-1.176 0l-3.367 2.447c-.783.57-1.837-.197-1.538-1.118l1.287-3.957a1 1 0 00-.363-1.118L2.062 9.385c-.783-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.287-3.958z"/></svg>
                            @endfor
                        </div>
                        <p class="relative text-sm text-[var(--color-ink-soft)] leading-relaxed">&ldquo;{{ $t->testimonial }}&rdquo;</p>
                        <div class="relative flex items-center gap-3 mt-5 pt-5 border-t border-[var(--color-line)] group-hover:border-[var(--color-primary)]/15 transition-colors duration-300">
                            @if ($t->photo)
                                <img src="{{ asset('storage/' . $t->photo) }}" alt="{{ $t->name }}" class="w-9 h-9 rounded-full object-cover ring-2 ring-[var(--color-gold)]/20">
                            @else
                                <span class="w-9 h-9 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] flex items-center justify-center font-display text-sm ring-2 ring-[var(--color-gold)]/20">{{ Str::substr($t->name, 0, 1) }}</span>
                            @endif
                            <div>
                                <p class="font-display text-sm">{{ $t->name }}</p>
                                <p class="text-xs text-[var(--color-ink-soft)]">{{ $t->city ?? $t->package_name }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- ============ FAQ (DATA ASLI DARI DATABASE) ============ --}}
    @if ($faqs->isNotEmpty())
        <section id="faq" class="relative overflow-hidden bg-[var(--color-surface)]">
            {{-- Tekstur titik halus & outline circle, konsisten dengan section Keunggulan --}}
            <div class="absolute inset-0 -z-10 geo-lattice opacity-[0.03]"></div>
            <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full border border-[var(--color-gold)]/10 -z-10 pointer-events-none hidden sm:block" aria-hidden="true"></div>

            <div class="max-w-6xl mx-auto px-5 sm:px-8 py-14 sm:py-20">
                <div class="grid lg:grid-cols-[0.85fr_1.15fr] gap-10 lg:gap-16">

                    {{-- Kolom intro — sticky di layar besar, memutus pola judul-tengah yang berulang --}}
                    <div class="text-center lg:text-left reveal lg:sticky lg:top-28 lg:self-start">
                        <div class="flex items-center justify-center lg:justify-start gap-3 mb-3">
                            <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
                            <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
                            <span class="hidden lg:block h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold)]/70"></span>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Pertanyaan</span>
                        <h2 class="font-display text-2xl sm:text-3xl mt-1.5 text-balance">Temukan Jawaban Sebelum Berangkat ke Tanah Suci</h2>
                        <p class="text-sm text-[var(--color-ink-soft)] mt-3 max-w-sm mx-auto lg:mx-0">Mulai dari persyaratan, fasilitas, hingga proses keberangkatan, temukan informasi yang Anda butuhkan di sini.</p>

                        <a href="{{ route('faqs.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--color-primary)] hover:underline mt-5">
                            Lihat semua pertanyaan
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                        </a>

                        @if ($waNumber)
                            <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="hidden lg:inline-flex items-center gap-2 mt-8 px-5 py-2.5 rounded-full border border-[var(--color-line)] text-sm text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)] transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.38A9.96 9.96 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10z"/></svg>
                                Masih ada pertanyaan lain?
                            </a>
                        @endif
                    </div>

                    <div class="space-y-3 reveal" x-data="{ open: 0 }">
                        @foreach ($faqs as $i => $faq)
                            <div class="border rounded-[var(--radius-card)] overflow-hidden transition-colors duration-300" :class="open === {{ $i }} ? 'border-[var(--color-primary)]/30 shadow-md shadow-[var(--color-primary)]/5' : 'border-[var(--color-line)]'">
                                <button type="button" @click="open === {{ $i }} ? open = null : open = {{ $i }}" class="w-full flex items-center gap-4 text-left px-5 py-4 bg-[var(--color-paper)] hover:bg-[var(--color-line)]/40 transition-colors">
                                    <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-display transition-colors duration-300" :class="open === {{ $i }} ? 'bg-[var(--color-primary)] text-white' : 'bg-[var(--color-primary)]/10 text-[var(--color-primary)]'">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                    <span class="flex-1 font-display text-sm sm:text-base">{{ $faq->question }}</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4 shrink-0 ml-3 transition-transform duration-300" :class="open === {{ $i }} ? 'rotate-45 text-[var(--color-primary)]' : ''">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                </button>
                                <div x-show="open === {{ $i }}" x-collapse class="px-5 py-4 pl-16 text-sm text-[var(--color-ink-soft)] leading-relaxed">
                                    {{ $faq->answer }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    {{-- ============ CONTACT US ============ --}}
    <section id="kontak" class="max-w-7xl mx-auto px-5 sm:px-8 py-14 sm:py-20">
        <div class="reveal relative overflow-hidden grain-overlay bg-[var(--color-primary)] rounded-[var(--radius-card)] px-6 sm:px-12 py-10 sm:py-14">
            <div class="absolute inset-0 geo-lattice-light opacity-[0.045] pointer-events-none"></div>
            <div class="absolute -left-16 -bottom-16 w-64 h-64 rounded-full bg-[var(--color-gold)]/15 blur-3xl pointer-events-none"></div>
            <div class="absolute -right-16 -top-16 w-56 h-56 rounded-full bg-[var(--color-primary-light)]/30 blur-3xl pointer-events-none"></div>
            <x-site.emblem class="hidden sm:block absolute right-8 bottom-8 w-16 h-16 text-white/[0.06] pointer-events-none" />

            <div class="relative grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
                {{-- Info kontak --}}
                <div class="text-center lg:text-left">
                    <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-gold-soft)]">Contact Us</span>
                    <h2 class="font-display text-2xl sm:text-3xl text-white mt-2">Siap Memulai Perjalanan Ibadah Anda?</h2>
                    <p class="text-white/70 text-sm sm:text-base mt-3 max-w-md mx-auto lg:mx-0">
                        Ada pertanyaan seputar paket atau pendaftaran? Kirim pesan, tim kami akan segera merespons.
                    </p>
                    <a href="{{ route('contact.status') }}" class="inline-flex items-center gap-1.5 text-sm text-[var(--color-gold-soft)] hover:underline mt-3">
                        Sudah pernah kirim pesan? Cek status &amp; balasan di sini &rarr;
                    </a>

                    <div class="mt-7 space-y-3 text-sm text-white/80 max-w-xs mx-auto lg:mx-0">
                        @if (!empty($settings['contact_phone']))
                            <div class="flex items-center gap-3 justify-center lg:justify-start">
                                <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                </span>
                                {{ $settings['contact_phone'] }}
                            </div>
                        @endif
                        @if (!empty($settings['contact_email']))
                            <div class="flex items-center gap-3 justify-center lg:justify-start">
                                <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                                </span>
                                {{ $settings['contact_email'] }}
                            </div>
                        @endif
                        @if (!empty($settings['contact_address']))
                            <div class="flex items-center gap-3 justify-center lg:justify-start">
                                <span class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                </span>
                                {{ $settings['contact_address'] }}
                            </div>
                        @endif
                    </div>

                    @if ($waNumber)
                        <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 mt-7 px-6 py-3 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] font-semibold text-sm hover:brightness-105 hover:-translate-y-0.5 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.38A9.96 9.96 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10z"/></svg>
                            Chat via WhatsApp
                        </a>
                    @endif
                </div>

                {{-- Form kontak (menggunakan route & validasi yang sudah ada) --}}
                <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] p-6 sm:p-7 shadow-2xl shadow-black/20">
                    @if (session('success'))
                        <div class="mb-5 flex items-start gap-2 rounded-lg bg-[var(--color-success)]/10 text-[var(--color-success)] text-sm font-medium px-4 py-3">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <label for="name" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Nama</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition @error('name') border-[var(--color-danger)] @enderror">
                            @error('name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Email</label>
                                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition @error('email') border-[var(--color-danger)] @enderror">
                                @error('email') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">No. HP (opsional)</label>
                                <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                       class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Subjek</label>
                            <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required
                                   class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition @error('subject') border-[var(--color-danger)] @enderror">
                            @error('subject') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Pesan</label>
                            <textarea id="message" name="message" rows="4" required
                                      class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition resize-none @error('message') border-[var(--color-danger)] @enderror">{{ old('message') }}</textarea>
                            @error('message') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg text-sm font-semibold hover:bg-[var(--color-primary-dark)] active:scale-[0.99] transition-all">
                            Kirim Pesan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="relative overflow-hidden bg-[var(--color-primary-dark)] text-white/60">
        <div class="h-px bg-gradient-to-r from-transparent via-[var(--color-gold)]/50 to-transparent"></div>
        <div class="absolute inset-0 geo-lattice-light opacity-[0.03]"></div>
        <x-site.emblem class="hidden lg:block absolute -right-3 -bottom-3 w-24 h-24 text-white/[0.035]" />

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
                    <li><a href="#paket" class="relative inline-block py-0.5 hover:text-white transition-colors group">Paket<span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span></a></li>
                    <li><a href="{{ route('articles.index') }}" class="relative inline-block py-0.5 hover:text-white transition-colors group">Artikel<span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span></a></li>
                    <li><a href="{{ route('gallery.index') }}" class="relative inline-block py-0.5 hover:text-white transition-colors group">Galeri<span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span></a></li>
                    <li><a href="{{ route('faqs.index') }}" class="relative inline-block py-0.5 hover:text-white transition-colors group">FAQ<span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span></a></li>
                    <li><a href="{{ route('contact.index') }}" class="relative inline-block py-0.5 hover:text-white transition-colors group">Kontak<span class="absolute left-0 -bottom-0.5 h-px w-0 bg-[var(--color-gold)] group-hover:w-full transition-all duration-300"></span></a></li>
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

    <style>
        .ease-premium { transition-timing-function: cubic-bezier(.22, 1, .36, 1); }
        .ease-zoom { transition-timing-function: cubic-bezier(.16, 1, .3, 1); }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-10px); }
        }
        @keyframes floatSlow {
            0%, 100% { transform: translateY(0); }
            50%      { transform: translateY(-6px); }
        }
        @keyframes pulseGlow {
            0%, 100% { opacity: .4; transform: scale(1); }
            50%      { opacity: .9; transform: scale(1.04); }
        }
        @keyframes scrollCue {
            0%, 100% { transform: translateY(0); opacity: .5; }
            50%      { transform: translateY(5px); opacity: 1; }
        }
        @media (prefers-reduced-motion: reduce) {
            [style*="animation"] { animation: none !important; }
            .scroll-cue-icon { animation: none !important; }
        }

        /* Focus-visible: ring emas konsisten untuk navigasi keyboard, kontras cukup di latar terang & gelap */
        a:focus-visible,
        button:focus-visible,
        input:focus-visible,
        textarea:focus-visible {
            outline: 2px solid var(--color-gold);
            outline-offset: 3px;
            border-radius: 6px;
        }
        .hero-cta-primary:focus-visible,
        .hero-cta-secondary:focus-visible {
            outline-offset: 4px;
        }
    </style>

    <script>
        // Reveal-on-scroll ringan, tanpa dependensi tambahan
        document.addEventListener('DOMContentLoaded', function () {
            var items = document.querySelectorAll('.reveal');
            if (!('IntersectionObserver' in window) || items.length === 0) {
                items.forEach(function (el) { el.classList.add('is-visible'); });
                return;
            }
            var observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            items.forEach(function (el) { observer.observe(el); });
        });

        // Parallax ringan pada elemen dekoratif bertanda [data-parallax]
        document.addEventListener('DOMContentLoaded', function () {
            var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var layers = Array.prototype.slice.call(document.querySelectorAll('[data-parallax]'));
            if (reduceMotion || layers.length === 0) return;

            var ticking = false;
            function updateParallax() {
                var y = window.scrollY;
                layers.forEach(function (el) {
                    var speed = parseFloat(el.getAttribute('data-parallax')) || 0;
                    el.style.transform = 'translate3d(0,' + (y * speed).toFixed(1) + 'px,0)';
                });
                ticking = false;
            }
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(updateParallax);
                    ticking = true;
                }
            }, { passive: true });
        });
    </script>

</body>
</html>