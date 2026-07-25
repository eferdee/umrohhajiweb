@extends('layouts.site')

@section('title', $package->title)

@section('content')
    @php
        $lowestPrice = $package->schedules->min('price');
        $scheduleCount = $package->schedules->count();

        $itineraryLines = collect(preg_split('/\r\n|\r|\n/', (string) $package->itinerary))
            ->map(fn ($l) => trim($l))->filter()->values();

        $facilityLines = collect(preg_split('/\r\n|\r|\n/', (string) $package->facilities))
            ->map(fn ($l) => trim($l, " \t\n\r\0\x0B-•"))->filter()->values();
    @endphp

    {{-- ============ HERO IMMERSIVE ============ --}}
    <section class="relative isolate bg-[var(--color-primary-dark)]" style="animation: fadeInUp .7s cubic-bezier(.22,.61,.36,1) both;">
        <div class="absolute inset-0 overflow-hidden">
            @if ($package->thumbnail)
                <img src="{{ asset('storage/' . $package->thumbnail) }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-[linear-gradient(160deg,var(--color-primary-dark)_0%,var(--color-primary)_55%,var(--color-primary-light)_120%)]"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary-dark)] via-[var(--color-primary-dark)]/55 to-[var(--color-primary-dark)]/25"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-[var(--color-primary-dark)]/70 via-transparent to-transparent"></div>
        </div>

        <div class="relative max-w-7xl mx-auto px-5 sm:px-8 pt-8 sm:pt-12 pb-24 sm:pb-28">
            <nav class="flex items-center gap-1.5 text-xs text-white/55 mb-6" aria-label="Breadcrumb">
                <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 shrink-0 text-white/30"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <a href="{{ route('packages.index') }}" class="hover:text-white transition-colors">Paket</a>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 shrink-0 text-white/30"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                <span class="text-white/80">{{ Str::limit($package->title, 40) }}</span>
            </nav>

            <x-site.back-link :href="route('packages.index')" label="Kembali ke daftar paket"
                class="!bg-white/10 !border-white/15 !text-white hover:!text-[var(--color-gold-soft)]" />

            <div class="max-w-2xl">
                @if ($package->category)
                    <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs tracking-wide uppercase bg-white/10 text-[var(--color-gold-soft)] border border-white/15 backdrop-blur-sm">{{ $package->category->name }}</span>
                @endif
                <h1 class="font-display text-3xl sm:text-4xl lg:text-[2.85rem] leading-[1.15] tracking-tight text-white mt-4 text-balance">{{ $package->title }}</h1>

                <div class="flex flex-wrap items-center gap-x-5 gap-y-2 mt-5 text-sm text-white/75">
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ $package->duration }} Hari
                    </span>
                    @if ($package->airline)
                        <span class="inline-flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                            {{ $package->airline }}
                        </span>
                    @endif
                    <span class="inline-flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                        {{ $scheduleCount }} Jadwal Tersedia
                    </span>
                </div>
            </div>
        </div>

        {{-- Kartu harga mengambang, menjembatani hero -> konten --}}
        <div class="relative max-w-7xl mx-auto px-5 sm:px-8">
            <div class="reveal relative z-40 -mb-16 sm:-mb-14 rounded-[var(--radius-card)] bg-[var(--color-surface)] border border-[var(--color-line)] shadow-2xl shadow-black/10 p-6 sm:p-7 flex flex-col sm:flex-row sm:items-center justify-between gap-5">
                <div class="min-w-0">
                    <span class="text-[11px] uppercase tracking-wide text-[var(--color-ink-soft)]">Mulai dari</span>
                    <p class="font-display text-3xl sm:text-4xl text-[var(--color-primary)] leading-tight mt-1">
                        @if ($lowestPrice)
                            Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                        @else
                            Hubungi Kami
                        @endif
                    </p>
                    <span class="text-xs text-[var(--color-ink-soft)]">per jamaah</span>
                </div>
                <a href="#jadwal" class="shrink-0 inline-flex items-center justify-center gap-2 px-7 py-3.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/25 active:scale-[0.98] transition-all duration-200">
                    Lihat Jadwal &amp; Daftar
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- ============ SUB-NAV ANCHOR ============ --}}
    <nav class="sticky top-16 sm:top-20 z-30 bg-[var(--color-paper)]/90 backdrop-blur-md border-b border-[var(--color-line)] mt-20 sm:mt-16" aria-label="Navigasi bagian">
        <div class="max-w-7xl mx-auto px-5 sm:px-8">
            <div class="pkg-subnav flex items-center gap-6 sm:gap-8 overflow-x-auto">
                <a href="#ringkasan" class="pkg-subnav-link is-active">Ringkasan</a>
                @if ($itineraryLines->isNotEmpty())
                    <a href="#itinerary" class="pkg-subnav-link">Itinerary</a>
                @endif
                @if ($facilityLines->isNotEmpty())
                    <a href="#fasilitas" class="pkg-subnav-link">Fasilitas</a>
                @endif
                <a href="#jadwal" class="pkg-subnav-link">Jadwal &amp; Harga</a>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto px-5 sm:px-8 pb-28 lg:pb-24">

        {{-- ============ RINGKASAN ============ --}}
        <section id="ringkasan" class="reveal scroll-mt-32 pt-10 sm:pt-14">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="pkg-fact-card">
                    <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                    <div><span class="detail-item-label">Durasi</span><span class="text-sm font-medium">{{ $package->duration }} Hari Perjalanan</span></div>
                </div>
                @if ($package->airline)
                    <div class="pkg-fact-card">
                        <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg></span>
                        <div><span class="detail-item-label">Maskapai</span><span class="text-sm font-medium">{{ $package->airline }}</span></div>
                    </div>
                @endif
                @if ($package->hotel_makkah)
                    <div class="pkg-fact-card">
                        <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg></span>
                        <div><span class="detail-item-label">Hotel Makkah</span><span class="text-sm font-medium">{{ $package->hotel_makkah }}</span></div>
                    </div>
                @endif
                @if ($package->hotel_madinah)
                    <div class="pkg-fact-card">
                        <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21" /></svg></span>
                        <div><span class="detail-item-label">Hotel Madinah</span><span class="text-sm font-medium">{{ $package->hotel_madinah }}</span></div>
                    </div>
                @endif
                <div class="pkg-fact-card">
                    <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg></span>
                    <div><span class="detail-item-label">Jadwal Tersedia</span><span class="text-sm font-medium">{{ $scheduleCount }} Pilihan Keberangkatan</span></div>
                </div>
                @if ($lowestPrice)
                    <div class="pkg-fact-card">
                        <span class="pkg-fact-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></span>
                        <div><span class="detail-item-label">Harga Mulai Dari</span><span class="text-sm font-medium text-[var(--color-primary)]">Rp {{ number_format($lowestPrice, 0, ',', '.') }}</span></div>
                    </div>
                @endif
            </div>

            @if ($package->description)
                <div class="mt-10">
                    <h2 class="font-display text-xl flex items-center gap-2.5 mb-3">
                        <span class="w-1.5 h-1.5 rotate-45 bg-[var(--color-gold)] shrink-0"></span>
                        Tentang Paket Ini
                    </h2>
                    <div class="text-sm text-[var(--color-ink-soft)] leading-relaxed">{!! nl2br(e($package->description)) !!}</div>
                </div>
            @endif
        </section>

        {{-- ============ ITINERARY TIMELINE ============ --}}
        @if ($itineraryLines->isNotEmpty())
            <section id="itinerary" class="reveal scroll-mt-32 pt-14 sm:pt-16">
                <x-site.section-title eyebrow="Rencana Perjalanan" title="Itinerary" align="left" />
                <div class="pkg-timeline mt-6">
                    @foreach ($itineraryLines as $i => $line)
                        @php
                            $hasColon = str_contains($line, ':');
                            $dayLabel = $hasColon ? trim(Str::before($line, ':')) : null;
                            $dayText = $hasColon ? trim(Str::after($line, ':')) : $line;
                        @endphp
                        <div class="pkg-timeline-item">
                            <span class="pkg-timeline-dot">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            @if ($dayLabel)
                                <p class="text-sm font-display text-[var(--color-ink)]">{{ $dayLabel }}</p>
                            @endif
                            <p class="text-sm text-[var(--color-ink-soft)] leading-relaxed mt-1">{{ $dayText }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ============ FASILITAS ============ --}}
        @if ($facilityLines->isNotEmpty())
            <section id="fasilitas" class="reveal scroll-mt-32 pt-14 sm:pt-16">
                <x-site.section-title eyebrow="Yang Anda Dapatkan" title="Fasilitas" align="left" />
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-6">
                    @foreach ($facilityLines as $facility)
                        <div class="pkg-facility-card">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 mt-0.5 shrink-0 text-[var(--color-success)]"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            <span class="leading-relaxed">{{ $facility }}</span>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- ============ JADWAL KEBERANGKATAN ============ --}}
        <section id="jadwal" class="reveal scroll-mt-32 pt-14 sm:pt-16">
            <x-site.section-title eyebrow="Langkah Selanjutnya" title="Pilih Jadwal Keberangkatan" align="left" />

            @if ($package->schedules->isEmpty())
                <x-site.empty-state
                    title="Belum ada jadwal keberangkatan"
                    description="Jadwal untuk paket ini belum tersedia. Silakan cek kembali nanti atau hubungi tim kami untuk info terbaru."
                    :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-6 h-6\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5\' /></svg>'" />
            @else
                <div class="mt-6 space-y-4">
                    @foreach ($package->schedules as $schedule)
                        @php
                            $isFull = $schedule->available_seat <= 0;
                            $pctLeft = $schedule->quota > 0 ? max(0, min(100, round($schedule->available_seat / $schedule->quota * 100))) : 0;
                        @endphp
                        <div class="rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-5 sm:p-6 transition-colors duration-200 hover:border-[var(--color-primary)]/25 hover:shadow-lg hover:shadow-[var(--color-primary)]/5">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-5 sm:gap-6">
                                <div class="flex-1 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm">
                                    <x-site.detail-item label="Berangkat dari">{{ $schedule->departure_city }}</x-site.detail-item>
                                    <x-site.detail-item label="Tanggal">{{ $schedule->departure_date->translatedFormat('d M Y') }} &ndash; {{ $schedule->return_date->translatedFormat('d M Y') }}</x-site.detail-item>
                                    <div>
                                        <span class="detail-item-label">Harga / Jamaah</span>
                                        <span class="text-sm text-[var(--color-primary)] font-semibold">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
                                    </div>
                                </div>

                                @if (!$isFull)
                                    <a href="{{ route('booking.create', $schedule) }}"
                                        class="shrink-0 inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium text-center hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.98] transition-all duration-200">
                                        Daftar Jadwal Ini
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                                    </a>
                                @else
                                    <span class="shrink-0 px-6 py-2.5 rounded-full border border-[var(--color-line)] text-[var(--color-ink-soft)] text-sm text-center">
                                        Kursi Penuh
                                    </span>
                                @endif
                            </div>

                            <div class="mt-4 pt-4 border-t border-[var(--color-line)] flex items-center gap-3">
                                <div class="pkg-seat-track flex-1">
                                    <div class="pkg-seat-fill" style="width: {{ $pctLeft }}%"></div>
                                </div>
                                <span class="badge {{ $isFull ? 'badge-danger' : 'badge-success' }} shrink-0">{{ $schedule->available_seat }} / {{ $schedule->quota }} Kursi</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>

    {{-- ============ STICKY BOOKING BAR (MOBILE) ============ --}}
    <div class="pkg-sticky-cta lg:hidden">
        <div class="flex items-center justify-between gap-4">
            <div class="min-w-0">
                <span class="text-[10px] uppercase tracking-wide text-[var(--color-ink-soft)]">Mulai dari</span>
                <p class="font-display text-lg text-[var(--color-primary)] truncate">
                    @if ($lowestPrice)
                        Rp {{ number_format($lowestPrice, 0, ',', '.') }}
                    @else
                        Hubungi Kami
                    @endif
                </p>
            </div>
            <a href="#jadwal" class="shrink-0 inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-dark)] transition-colors duration-200">
                Lihat Jadwal
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </a>
        </div>
    </div>
    <div class="h-20 lg:hidden"></div>
@endsection
