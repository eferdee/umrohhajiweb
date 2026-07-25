@extends('layouts.admin')

@section('title', 'Artikel')

@section('content')

    @php
        $totalPublished = \App\Models\Article::where('is_published', true)->count();
        $totalDraft = \App\Models\Article::where('is_published', false)->count();
        $totalViews = \App\Models\Article::sum('views');
    @endphp

    <x-admin.page-header title="Artikel" description="Kelola artikel dan berita untuk halaman publik.">
        <x-slot:actions>
            <a href="{{ route('admin.articles.create') }}"
               class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Artikel
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    {{-- Statistics cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card label="Total Artikel" :value="$articles->total()" accent="var(--color-primary)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Terbit" :value="$totalPublished" accent="var(--color-success)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Draft" :value="$totalDraft" accent="var(--color-warning-ink)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Total Dilihat" :value="number_format($totalViews)" accent="var(--color-info)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
    </div>

    <div x-data="{ q: '', status: '' }">

        {{-- Search & filter (client-side, halaman saat ini) --}}
        <div class="admin-toolbar p-4 mb-5 flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" x-model="q" placeholder="Cari judul atau penulis..." class="filter-input pl-10">
            </div>
            <select x-model="status" class="filter-select sm:w-52">
                <option value="">Semua Status</option>
                <option value="terbit">Terbit</option>
                <option value="draft">Draft</option>
            </select>
        </div>

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Artikel</th>
                        <th>Penulis</th>
                        <th>Dilihat</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($articles as $article)
                        <tr
                            data-search="{{ Str::lower($article->title.' '.($article->user->name ?? '')) }}"
                            data-status="{{ $article->is_published ? 'terbit' : 'draft' }}"
                            x-show="(q === '' || $el.dataset.search.includes(q.toLowerCase())) && (status === '' || $el.dataset.status === status)"
                        >
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-[var(--color-admin-surface-alt)] overflow-hidden shrink-0 flex items-center justify-center text-[var(--color-ink-soft)] text-[10px]">
                                        @if ($article->thumbnail)
                                            <img src="{{ asset('storage/' . $article->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                                        @else
                                            Tanpa foto
                                        @endif
                                    </div>
                                    <span class="font-medium line-clamp-2 max-w-xs">{{ $article->title }}</span>
                                </div>
                            </td>
                            <td class="text-[var(--color-ink-soft)]">{{ $article->user->name ?? '-' }}</td>
                            <td class="text-[var(--color-ink-soft)]">{{ number_format($article->views) }}</td>
                            <td><x-admin.status-badge :status="$article->is_published" :label="$article->is_published ? 'Terbit' : 'Draft'" /></td>
                            <td class="text-[var(--color-ink-soft)]">{{ $article->created_at->format('d M Y') }}</td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.articles.edit', $article) }}" class="action-icon-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.articles.destroy', $article) }}" method="POST" onsubmit="return confirm('Hapus artikel ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon-btn is-danger" title="Hapus">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse
                </tbody>
            </table>
            </div>

            @if ($articles->isEmpty())
                <x-admin.empty-state title="Belum ada artikel" description="Mulai bagikan berita & wawasan seputar perjalanan ibadah kepada calon jamaah.">
                    <x-slot:action>
                        <a href="{{ route('admin.articles.create') }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">+ Tambah artikel pertama</a>
                    </x-slot:action>
                </x-admin.empty-state>
            @endif
        </div>
    </div>

    {{ $articles->links('components.admin.pagination') }}

@endsection
