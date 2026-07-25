@extends('layouts.site')

@section('title', 'FAQ')

@section('content')
    <x-site.hero
        eyebrow="Bantuan"
        title="Pertanyaan Umum"
        description="Jawaban seputar pendaftaran, pembayaran, dan persiapan keberangkatan."
        :crumbs="['Beranda' => url('/'), 'FAQ' => null]" />

    <section class="max-w-3xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">
        @forelse ($faqs as $category => $items)
            <div class="mb-10 last:mb-0">
                <div class="flex items-center gap-3 mb-4 reveal">
                    <span class="w-1.5 h-1.5 rotate-45 bg-[var(--color-gold)] shrink-0"></span>
                    <h2 class="font-display text-lg sm:text-xl">{{ $category }}</h2>
                </div>
                <div class="space-y-3">
                    @foreach ($items as $i => $faq)
                        <x-site.faq-item :faq="$faq" :index="$i" />
                    @endforeach
                </div>
            </div>
        @empty
            <x-site.empty-state
                title="Belum ada pertanyaan yang tersedia"
                description="Daftar pertanyaan umum akan segera kami tambahkan di sini."
                :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-6 h-6\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z\' /></svg>'" />
        @endforelse

        <div class="reveal text-center mt-12 pt-10 border-t border-[var(--color-line)]">
            <p class="text-sm text-[var(--color-ink-soft)] mb-4">Tidak menemukan jawaban yang kamu cari?</p>
            <a href="{{ route('contact.index') }}" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 hover:-translate-y-0.5 transition-all duration-200">
                Hubungi Kami
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </a>
        </div>
    </section>
@endsection
