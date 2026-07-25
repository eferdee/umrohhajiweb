@extends('layouts.site')

@section('title', 'Paket Umroh & Haji')

@section('content')
    <x-site.hero
        eyebrow="Pilihan Perjalanan"
        title="Paket Umroh & Haji"
        description="Pilih paket, lalu tentukan jadwal keberangkatan yang sesuai untuk mendaftar."
        :crumbs="['Beranda' => url('/'), 'Paket' => null]" />

    <section class="max-w-7xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">

        {{-- Filter & pencarian --}}
        <form method="GET" class="reveal flex flex-col sm:flex-row flex-wrap items-stretch sm:items-center gap-3 mb-10 bg-[var(--color-surface)] border border-[var(--color-line)] rounded-[var(--radius-card)] p-3 shadow-sm shadow-black/[0.03]">
            <div class="relative flex-1 min-w-[200px]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket..."
                    class="w-full pl-10 pr-4 py-2.5 rounded-full border border-[var(--color-line)] text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 focus:border-[var(--color-primary)] transition-all duration-200">
            </div>
            <select name="category" onchange="this.form.submit()"
                class="px-4 py-2.5 rounded-full border border-[var(--color-line)] text-sm bg-[var(--color-surface)] focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/20 focus:border-[var(--color-primary)] transition-all duration-200">
                <option value="">Semua Kategori</option>
                @foreach ($categories as $c)
                    <option value="{{ $c->id }}" @selected(request('category') == $c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
            <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.98] transition-all duration-200">
                Cari
            </button>
            @if (request('search') || request('category'))
                <a href="{{ route('packages.index') }}" class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-full text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition-colors duration-200">
                    Reset
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                </a>
            @endif
        </form>

        @if ($packages->isEmpty())
            <x-site.empty-state
                title="Belum ada paket yang tersedia"
                description="Coba ubah kata kunci pencarian atau kategori, atau cek kembali nanti untuk paket terbaru kami."
                :icon="'<svg xmlns=\'http://www.w3.org/2000/svg\' fill=\'none\' viewBox=\'0 0 24 24\' stroke-width=\'1.5\' stroke=\'currentColor\' class=\'w-6 h-6\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' d=\'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z\' /></svg>'" />
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($packages as $i => $p)
                    <div style="transition-delay:{{ ($i % 3) * 80 }}ms">
                        <x-site.package-card :package="$p" />
                    </div>
                @endforeach
            </div>

            <div class="mt-12">
                {{ $packages->links('vendor.pagination.site') }}
            </div>
        @endif
    </section>
@endsection
