@extends('layouts.admin')

@section('title', 'Data Jamaah')

@section('content')

    <form method="GET" class="admin-toolbar p-4 mb-5 flex flex-col sm:flex-row gap-3" id="pilgrim-filter-form">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / NIK..." class="filter-input pl-10" id="pilgrim-search-input">
        </div>
        <select name="document_status" onchange="this.form.submit()" class="filter-select sm:w-56">
            <option value="">Semua Status Dokumen</option>
            <option value="incomplete" @selected(request('document_status') == 'incomplete')>Belum Lengkap</option>
            <option value="pending" @selected(request('document_status') == 'pending')>Menunggu Verifikasi</option>
            <option value="verified" @selected(request('document_status') == 'verified')>Terverifikasi</option>
        </select>
        @if (request('search') || request('document_status'))
            <a href="{{ route('admin.pilgrims.index') }}" class="filter-input sm:w-auto flex items-center justify-center gap-1.5 text-[var(--color-ink-soft)] hover:text-[var(--color-danger)] transition-colors cursor-pointer border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                Reset
            </a>
        @endif
    </form>

    <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] overflow-hidden">
        <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-[var(--color-ink-soft)] bg-[var(--color-paper)]">
                    <th class="py-3 px-5 font-medium">Nama Jamaah</th>
                    <th class="py-3 px-5 font-medium">NIK</th>
                    <th class="py-3 px-5 font-medium">Booking</th>
                    <th class="py-3 px-5 font-medium">Paket</th>
                    <th class="py-3 px-5 font-medium">Dokumen</th>
                    <th class="py-3 px-5 font-medium text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[var(--color-line)]">
                @forelse ($pilgrims as $pilgrim)
                    @php
                        $docColor = match ($pilgrim->document_status) {
                            'verified' => 'bg-[var(--color-success)]/10 text-[var(--color-success)]',
                            'pending' => 'bg-[var(--color-warning)]/10 text-[var(--color-warning)]',
                            default => 'bg-[var(--color-danger)]/10 text-[var(--color-danger)]',
                        };
                    @endphp
                    <tr class="hover:bg-[var(--color-paper)]/60 transition-colors">
                        <td class="py-3 px-5 font-medium">{{ $pilgrim->full_name }}</td>
                        <td class="py-3 px-5 text-[var(--color-ink-soft)]">{{ $pilgrim->nik }}</td>
                        <td class="py-3 px-5">{{ $pilgrim->booking->booking_code ?? '-' }}</td>
                        <td class="py-3 px-5">{{ $pilgrim->booking->packageSchedule->package->title ?? '-' }}</td>
                        <td class="py-3 px-5">
                            <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $docColor }}">{{ ucfirst($pilgrim->document_status) }}</span>
                        </td>
                        <td class="py-3 px-5 text-right">
                            <a href="{{ route('admin.pilgrims.show', $pilgrim) }}" class="text-[var(--color-primary)] hover:underline">Detail</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-10 text-center text-[var(--color-ink-soft)]">Belum ada data jamaah.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>

    <div class="mt-6">{{ $pilgrims->links() }}</div>

@endsection
