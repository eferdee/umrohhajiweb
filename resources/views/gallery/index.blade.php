@extends('layouts.site')

@section('title', 'Galeri')

@section('content')
    <x-site.hero
        eyebrow="Dokumentasi"
        title="Galeri Perjalanan"
        description="Momen perjalanan jamaah kami di Tanah Suci. Ketuk foto untuk melihat lebih besar."
        :crumbs="['Beranda' => url('/'), 'Galeri' => null]" />

    <section class="max-w-7xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10" x-data="{ open: false, src: '', title: '' }">
        @if ($galleries->isEmpty())
            <x-site.empty-state
                title="Belum ada foto/video yang dipublikasikan"
                description="Galeri momen jamaah akan segera hadir di sini."
                :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-6 h-6\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 4.5h18A1.5 1.5 0 0122.5 6v12a1.5 1.5 0 01-1.5 1.5H3A1.5 1.5 0 011.5 18V6A1.5 1.5 0 013 4.5zM9.75 9.75a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z\' /></svg>'" />
        @else
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
                @foreach ($galleries as $i => $item)
                    <div style="transition-delay:{{ ($i % 8) * 50 }}ms">
                        <x-site.gallery-tile :item="$item" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $galleries->links('vendor.pagination.site') }}
            </div>
        @endif

        {{-- Lightbox --}}
        <div x-show="open" x-cloak @click.self="open = false" @keydown.escape.window="open = false"
             class="fixed inset-0 z-[60] bg-black/90 backdrop-blur-sm flex items-center justify-center p-5" x-transition.opacity>
            <button @click="open = false" class="absolute top-5 right-5 text-white/70 hover:text-white p-2 transition-colors duration-200" aria-label="Tutup">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <div x-show="open" x-transition.scale.90 class="max-w-3xl w-full">
                <img :src="src" :alt="title" class="w-full max-h-[75vh] object-contain rounded-xl">
                <p class="text-white/80 text-sm text-center mt-4" x-text="title"></p>
            </div>
        </div>
    </section>
@endsection
