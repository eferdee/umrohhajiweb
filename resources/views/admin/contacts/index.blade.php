@extends('layouts.admin')

@section('title', 'Pesan Masuk')

@section('content')

    @php
        $stCounts = \App\Models\ContactMessage::query()->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $totalContacts = $stCounts->sum();
        $totalNew = $stCounts['new'] ?? 0;
        $totalReplied = $stCounts['replied'] ?? 0;
        $totalClosed = $stCounts['closed'] ?? 0;
        $statusOptions = ['new', 'read', 'replied', 'closed'];
    @endphp

    <x-admin.page-header title="Pesan Masuk" description="Kelola pesan yang dikirim jamaah melalui formulir kontak di landing page." />

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card label="Total Pesan" :value="$totalContacts" accent="var(--color-primary)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Belum Ditindaklanjuti" :value="$totalNew" accent="var(--color-warning-ink)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Sudah Dibalas" :value="$totalReplied" accent="var(--color-success)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Ditutup" :value="$totalClosed" accent="var(--color-ink-soft)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
    </div>

    <form method="GET" class="admin-toolbar p-4 mb-5 flex flex-col sm:flex-row gap-3" id="contact-filter-form">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email / subjek..." class="admin-input pl-10" id="contact-search-input">
        </div>
        <select name="status" onchange="this.form.submit()" class="admin-input sm:w-56">
            <option value="">Semua Status</option>
            @foreach ($statusOptions as $status)
                <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst($status) }}</option>
            @endforeach
        </select>
        @if (request('search') || request('status'))
            <a href="{{ route('admin.contacts.index') }}" class="admin-input sm:w-auto flex items-center justify-center gap-1.5 text-[var(--color-ink-soft)] hover:text-[var(--color-danger)] transition-colors cursor-pointer border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                Reset
            </a>
        @endif
    </form>

    <div class="admin-table-wrap">
        <div class="admin-table-scroll">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Pengirim</th>
                    <th>Subjek</th>
                    <th>Status</th>
                    <th>Tanggal Masuk</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($contacts as $contact)
                    <tr class="{{ ! $contact->is_read ? 'font-medium' : '' }}">
                        <td>
                            {{ $contact->name }}
                            <div class="text-xs text-[var(--color-ink-soft)] font-normal">{{ $contact->email }}</div>
                        </td>
                        <td class="font-normal">{{ $contact->subject }}</td>
                        <td><x-admin.status-badge :status="$contact->status" /></td>
                        <td class="font-normal">{{ $contact->created_at->format('d M Y H:i') }}</td>
                        <td class="text-right">
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="action-icon-btn" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($contacts->isEmpty())
            <x-admin.empty-state title="Belum ada pesan masuk" description="Pesan yang dikirim jamaah lewat formulir kontak di landing page akan muncul di sini." />
        @endif
    </div>

    {{ $contacts->links('components.admin.pagination') }}

    <script>
        (function () {
            const input = document.getElementById('contact-search-input');
            const form = document.getElementById('contact-filter-form');
            if (!input || !form) return;
            let t;
            input.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => form.submit(), 500);
            });
        })();
    </script>

@endsection
