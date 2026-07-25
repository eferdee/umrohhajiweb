@extends('layouts.admin')

@section('title', 'Paket')

@section('content')

    @php
        $totalActive = \App\Models\Package::where('status', true)->count();
        $totalInactive = \App\Models\Package::where('status', false)->count();
        $totalSchedules = \App\Models\PackageSchedule::count();
    @endphp

    <x-admin.page-header title="Paket" description="Kelola paket perjalanan Umroh & Haji.">
        <x-slot:actions>
            <a href="{{ route('admin.packages.create') }}"
               class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Paket
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    @if (session('error'))
        <div class="bg-[var(--color-danger)]/10 text-[var(--color-danger)] px-4 py-3 rounded-xl mb-6 text-sm border border-[var(--color-danger)]/20">{{ session('error') }}</div>
    @endif

    <div class="grid grid-cols-3 gap-4 mb-6">
        <x-admin.stat-card label="Paket Aktif" :value="$totalActive" accent="var(--color-success)" />
        <x-admin.stat-card label="Nonaktif" :value="$totalInactive" accent="var(--color-ink-soft)" />
        <x-admin.stat-card label="Total Jadwal" :value="$totalSchedules" accent="var(--color-gold-deep)" />
    </div>

    <form method="GET" class="admin-toolbar p-4 mb-5 flex flex-col sm:flex-row gap-3" id="package-filter-form">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama paket..." class="filter-input pl-10" id="package-search-input">
        </div>
        <select name="category" onchange="this.form.submit()" class="filter-select sm:w-56">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
        @if (request('search') || request('category'))
            <a href="{{ route('admin.packages.index') }}" class="filter-input sm:w-auto flex items-center justify-center gap-1.5 text-[var(--color-ink-soft)] hover:text-[var(--color-danger)] transition-colors cursor-pointer border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                Reset
            </a>
        @endif
    </form>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse ($packages as $package)
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-admin-border)] shadow-[var(--shadow-card)] overflow-hidden flex flex-col transition-all duration-300 hover:shadow-[var(--shadow-elevated)] hover:-translate-y-1">
                <div class="h-32 bg-[var(--color-admin-surface-alt)] flex items-center justify-center text-[var(--color-ink-soft)] text-xs">
                    @if ($package->thumbnail)
                        <img src="{{ asset('storage/' . $package->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $package->title }}">
                    @else
                        Belum ada foto
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <span class="text-xs text-[var(--color-gold-ink)] font-semibold">{{ $package->category->name ?? '-' }}</span>
                    <h3 class="font-display text-lg mt-1 mb-2">{{ $package->title }}</h3>
                    <p class="text-xs text-[var(--color-ink-soft)] mb-3">{{ $package->duration }} hari &middot; {{ $package->schedules_count }} jadwal keberangkatan</p>

                    <div class="mt-auto flex items-center justify-between pt-3 border-t border-[var(--color-line)]">
                        <x-admin.status-badge :status="$package->status" :label="$package->status ? 'Aktif' : 'Nonaktif'" />
                        <div class="flex items-center gap-1">
                            <a href="{{ route('admin.packages.schedules.index', $package) }}" class="action-icon-btn" title="Jadwal Keberangkatan">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            </a>
                            <a href="{{ route('admin.packages.edit', $package) }}" class="action-icon-btn" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <x-admin.empty-state title="Tidak ada paket ditemukan" description="Coba ubah kata kunci pencarian atau filter kategori.">
                    <x-slot:action>
                        <a href="{{ route('admin.packages.create') }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">+ Tambah paket pertama</a>
                    </x-slot:action>
                </x-admin.empty-state>
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $packages->links('components.admin.pagination') }}</div>

    <script>
        (function () {
            const input = document.getElementById('package-search-input');
            const form = document.getElementById('package-filter-form');
            if (!input || !form) return;
            let t;
            input.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => form.submit(), 500);
            });
        })();
    </script>

@endsection
