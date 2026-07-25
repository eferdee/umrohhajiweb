@extends('layouts.site')

@section('title', $article->title)

@php
    // Estimasi waktu baca — kata rata-rata dibaca ±200 kata/menit.
    $wordCount = str_word_count(strip_tags($article->content));
    $readingMinutes = max(1, (int) ceil($wordCount / 200));

    $shareUrl = urlencode(url()->current());
    $shareText = urlencode($article->title);
@endphp

@section('content')
    {{-- Progress bar bacaan --}}
    <div class="fixed top-0 inset-x-0 h-[3px] z-50 bg-transparent" aria-hidden="true">
        <div id="reading-progress" class="h-full bg-[var(--color-gold)] w-0 transition-[width] duration-150 ease-linear"></div>
    </div>

    <x-site.hero
        eyebrow="Artikel"
        :title="$article->title"
        :crumbs="['Beranda' => url('/'), 'Artikel' => route('articles.index'), Str::limit($article->title, 40) => null]">
        <div class="flex flex-wrap items-center justify-center gap-x-2 gap-y-1.5 text-white/60 text-xs mt-4">
            <span>{{ $article->published_at?->translatedFormat('d M Y') }}</span>
            <span class="w-1 h-1 rounded-full bg-white/30"></span>
            <span>{{ $readingMinutes }} menit baca</span>
            <span class="w-1 h-1 rounded-full bg-white/30"></span>
            <span>{{ number_format($article->views) }} kali dibaca</span>
            @if ($article->user)
                <span class="w-1 h-1 rounded-full bg-white/30"></span>
                <span>Oleh {{ $article->user->name }}</span>
            @endif
        </div>
    </x-site.hero>

    <section class="max-w-3xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">
        <div class="reveal flex items-center justify-between gap-3 mb-6">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--color-ink)] bg-[var(--color-surface)] border border-[var(--color-line)] rounded-full pl-3.5 pr-4 py-2 shadow-sm shadow-black/[0.06] hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/30 hover:-translate-x-0.5 transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                <span class="hidden sm:inline">Kembali ke Artikel</span>
                <span class="sm:hidden">Kembali</span>
            </a>

            {{-- Share --}}
            <div class="flex items-center gap-2">
                <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener noreferrer"
                   aria-label="Bagikan ke WhatsApp"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[var(--color-surface)] border border-[var(--color-line)] text-[var(--color-ink-soft)] shadow-sm shadow-black/[0.06] hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/30 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.38a9.95 9.95 0 0 0 4.79 1.22h.01c5.52 0 10-4.48 10-10s-4.48-10-10.02-10zm5.86 14.3c-.25.7-1.24 1.28-2.03 1.45-.54.11-1.24.2-3.6-.77-3.02-1.25-4.96-4.32-5.11-4.52-.15-.2-1.22-1.62-1.22-3.09 0-1.47.77-2.19 1.05-2.49.27-.3.6-.37.8-.37.2 0 .4 0 .58.01.19.01.44-.07.68.53.25.6.85 2.08.92 2.23.07.15.12.33.02.53-.1.2-.15.33-.3.5-.15.18-.31.4-.44.53-.15.15-.3.31-.13.6.17.3.76 1.26 1.63 2.04 1.12 1 2.06 1.31 2.36 1.46.3.15.47.13.65-.08.18-.2.75-.87.95-1.17.2-.3.4-.25.66-.15.27.1 1.73.82 2.02.97.3.15.5.22.57.35.07.13.07.75-.18 1.45z"/></svg>
                </a>
                <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&amp;url={{ $shareUrl }}" target="_blank" rel="noopener noreferrer"
                   aria-label="Bagikan ke X (Twitter)"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-[var(--color-surface)] border border-[var(--color-line)] text-[var(--color-ink-soft)] shadow-sm shadow-black/[0.06] hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/30 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4"><path d="M18.9 2H22l-7.6 8.7L23 22h-6.9l-5.4-6.6L4.5 22H1.4l8.1-9.3L1 2h7l4.9 6.1L18.9 2Zm-1.2 18h1.9L7.4 4H5.4l12.3 16Z"/></svg>
                </a>
                <button type="button" data-copy-link="{{ url()->current() }}"
                        aria-label="Salin tautan"
                        class="js-copy-link inline-flex items-center justify-center w-9 h-9 rounded-full bg-[var(--color-surface)] border border-[var(--color-line)] text-[var(--color-ink-soft)] shadow-sm shadow-black/[0.06] hover:text-[var(--color-primary)] hover:border-[var(--color-primary)]/30 transition-colors duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" /></svg>
                </button>
            </div>
        </div>

        <article class="reveal bg-[var(--color-surface)] border border-[var(--color-line)] rounded-[var(--radius-card)] p-6 sm:p-9 shadow-sm shadow-black/[0.03]">
            @if ($article->thumbnail)
                <figure class="mb-8">
                    <div class="h-56 sm:h-80 rounded-[var(--radius-card)] overflow-hidden border border-[var(--color-line)]">
                        <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" class="w-full h-full object-cover">
                    </div>
                </figure>
            @endif

            {{--
                Konten artikel dicetak sebagai HTML mentah ({!! !!}) tanpa
                escape ganda, sesuai isi asli dari editor admin (h2, p, ul,
                dst. akan diparsing browser, bukan tampil sebagai teks
                literal).

                Amannya render ini bertumpu pada dua lapis:
                1) Akses tulis: hanya admin lewat middleware auth+admin
                   (routes/admin.php) yang bisa mengisi field `content`.
                2) Sanitasi otomatis: App\Models\Article::setContentAttribute()
                   membersihkan HTML lewat App\Support\HtmlSanitizer
                   (allowlist tag/attribute, buang script/event handler/
                   javascript: URL) setiap kali disimpan — jadi walau lapis
                   pertama suatu saat berubah (role baru, akun admin
                   kebobolan), payload aktif tidak akan pernah tersimpan.
            --}}
            <div class="article-content">
                {!! $article->content !!}
            </div>
        </article>

        @if ($related->isNotEmpty())
            <div class="mt-14 sm:mt-16">
                <x-site.section-title eyebrow="Wawasan & Tips" title="Artikel Lainnya" align="left" />
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                    @foreach ($related as $item)
                        <x-site.article-card :article="$item" />
                    @endforeach
                </div>
            </div>
        @endif
    </section>

    <script>
        (function () {
            // Progress bar baca — proporsi scroll di dalam <article>.
            var bar = document.getElementById('reading-progress');
            var article = document.querySelector('.article-content')?.closest('article');
            if (bar && article) {
                var update = function () {
                    var rect = article.getBoundingClientRect();
                    var total = rect.height - window.innerHeight;
                    var scrolled = Math.min(Math.max(-rect.top, 0), total);
                    var pct = total > 0 ? (scrolled / total) * 100 : 0;
                    bar.style.width = pct + '%';
                };
                document.addEventListener('scroll', update, { passive: true });
                window.addEventListener('resize', update);
                update();
            }

            // Salin tautan.
            document.querySelectorAll('.js-copy-link').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var url = btn.getAttribute('data-copy-link');
                    navigator.clipboard?.writeText(url).then(function () {
                        var original = btn.innerHTML;
                        btn.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>';
                        setTimeout(function () { btn.innerHTML = original; }, 1500);
                    });
                });
            });
        })();
    </script>
@endsection
