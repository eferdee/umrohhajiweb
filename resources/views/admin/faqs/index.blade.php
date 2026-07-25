@extends('layouts.admin')

@section('title', 'FAQ')

@section('content')

    @php
        $totalPublished = \App\Models\Faq::where('is_published', true)->count();
        $totalHidden = \App\Models\Faq::where('is_published', false)->count();
        $totalCategories = \App\Models\Faq::distinct('category')->count('category');
    @endphp

    <x-admin.page-header title="FAQ" description="Kelola pertanyaan yang sering diajukan.">
        <x-slot:actions>
            <a href="{{ route('admin.faqs.create') }}"
               class="inline-flex items-center gap-2 bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-300 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah FAQ
            </a>
        </x-slot:actions>
    </x-admin.page-header>

    <div class="grid grid-cols-3 gap-4 mb-6">
        <x-admin.stat-card label="Tampil" :value="$totalPublished" accent="var(--color-success)" />
        <x-admin.stat-card label="Disembunyikan" :value="$totalHidden" accent="var(--color-warning-ink)" />
        <x-admin.stat-card label="Kategori" :value="$totalCategories" accent="var(--color-gold-deep)" />
    </div>

    <div x-data="{ q: '' }">
        <div class="admin-toolbar p-4 mb-5">
            <div class="relative">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                <input type="text" x-model="q" placeholder="Cari pertanyaan atau kategori..." class="filter-input pl-10 sm:max-w-md">
            </div>
        </div>

        <div class="admin-table-wrap">
            <div class="admin-table-scroll">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kategori</th>
                        <th>Pertanyaan</th>
                        <th>Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($faqs as $faq)
                        <tr data-search="{{ Str::lower($faq->question.' '.$faq->category) }}" x-show="q === '' || $el.dataset.search.includes(q.toLowerCase())">
                            <td><span class="badge badge-gold">{{ $faq->category }}</span></td>
                            <td class="font-medium max-w-md">{{ $faq->question }}</td>
                            <td><x-admin.status-badge :status="$faq->is_published" :label="$faq->is_published ? 'Tampil' : 'Disembunyikan'" /></td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="action-icon-btn" title="Edit">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" /></svg>
                                    </a>
                                    <form action="{{ route('admin.faqs.destroy', $faq) }}" method="POST" onsubmit="return confirm('Hapus FAQ ini?')">
                                        @csrf @method('DELETE')
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

            @if ($faqs->isEmpty())
                <x-admin.empty-state title="Belum ada FAQ" description="Tambahkan pertanyaan yang sering ditanyakan calon jamaah.">
                    <x-slot:action>
                        <a href="{{ route('admin.faqs.create') }}" class="text-sm font-medium text-[var(--color-primary)] hover:underline">+ Tambah FAQ pertama</a>
                    </x-slot:action>
                </x-admin.empty-state>
            @endif
        </div>
    </div>

    {{ $faqs->links('components.admin.pagination') }}

@endsection
