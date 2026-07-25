@extends('layouts.admin')

@section('title', 'Galeri')

@section('content')

    @php
        $totalImage = \App\Models\Gallery::where('type', 'image')->count();
        $totalVideo = \App\Models\Gallery::where('type', 'video')->count();
        $totalFeatured = \App\Models\Gallery::where('is_featured', true)->count();
    @endphp

    <x-admin.page-header title="Galeri" description="Kelola foto & video galeri untuk halaman publik.">
        <x-slot:actions>
            <a href="{{ route('admin.gallery.create') }}"
               class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Item
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <x-admin.stat-card label="Total Foto" :value="$totalImage" accent="var(--color-primary)" />
        <x-admin.stat-card label="Total Video" :value="$totalVideo" accent="var(--color-info)" />
        <x-admin.stat-card label="Unggulan" :value="$totalFeatured" accent="var(--color-gold-deep)" />
    </div>

    <div x-data="{ q: '' }">
        <div class="admin-toolbar p-4 mb-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" x-model="q" placeholder="Cari judul item galeri..." class="filter-input pl-10 sm:max-w-md">
            </div>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-5">
            @forelse ($galleries as $item)
                <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-admin-border)] shadow-[var(--shadow-card)] overflow-hidden flex flex-col transition-all duration-300 hover:shadow-[var(--shadow-elevated)] hover:-translate-y-1"
                     data-search="{{ Str::lower($item->title) }}" x-show="q === '' || $el.dataset.search.includes(q.toLowerCase())">
                    <div class="relative h-32 bg-[var(--color-admin-surface-alt)] flex items-center justify-center text-[var(--color-ink-soft)] text-xs">
                        @if ($item->type === 'image')
                            <img src="{{ asset('storage/' . $item->file_path) }}" class="w-full h-full object-cover" alt="{{ $item->title }}">
                        @else
                            <div class="flex flex-col items-center gap-1 text-[var(--color-ink-soft)]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5l4.72-4.72a.75.75 0 011.28.53v11.38a.75.75 0 01-1.28.53l-4.72-4.72M4.5 18.75h9a2.25 2.25 0 002.25-2.25v-9a2.25 2.25 0 00-2.25-2.25h-9A2.25 2.25 0 002.25 7.5v9a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                                <span>Video</span>
                            </div>
                        @endif

                        @if ($item->is_featured)
                            <span class="absolute top-2 left-2 bg-[var(--color-gold)] text-[var(--color-primary-dark)] text-[10px] font-semibold px-2 py-0.5 rounded-full shadow-sm">
                                Unggulan
                            </span>
                        @endif
                    </div>

                    <div class="p-4 flex-1 flex flex-col">
                        <h3 class="text-sm font-medium line-clamp-1">{{ $item->title }}</h3>
                        <p class="text-xs text-[var(--color-ink-soft)] mt-1 capitalize">{{ $item->type }}</p>

                        <div class="mt-auto flex items-center justify-between pt-3 border-t border-[var(--color-line)] mt-3">
                            <x-admin.status-badge :status="$item->is_published" :label="$item->is_published ? 'Tampil' : 'Draft'" />
                            <form action="{{ route('admin.gallery.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus item galeri ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-icon-btn is-danger" title="Hapus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <x-admin.empty-state title="Belum ada item galeri" description="Unggah foto atau video untuk ditampilkan di halaman publik.">
                        <x-slot:action>
                            <a href="{{ route('admin.gallery.create') }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">+ Tambah item pertama</a>
                        </x-slot:action>
                    </x-admin.empty-state>
                </div>
            @endforelse
        </div>
    </div>

    <div class="mt-6">{{ $galleries->links('components.admin.pagination') }}</div>

@endsection
