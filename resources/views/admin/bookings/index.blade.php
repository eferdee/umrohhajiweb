@extends('layouts.admin')

@section('title', 'Booking')

@section('content')

    @php
        $stCounts = \App\Models\Booking::query()->selectRaw('status, count(*) c')->groupBy('status')->pluck('c', 'status');
        $totalBooking = $stCounts->sum();
        $totalPending = ($stCounts['pending'] ?? 0) + ($stCounts['waiting_payment'] ?? 0) + ($stCounts['waiting_verification'] ?? 0);
        $totalPaid = ($stCounts['paid'] ?? 0) + ($stCounts['completed'] ?? 0) + ($stCounts['scheduled'] ?? 0);
        $totalCancelled = $stCounts['cancelled'] ?? 0;
        $statusOptions = ['pending', 'waiting_payment', 'waiting_verification', 'partially_paid', 'paid', 'scheduled', 'completed', 'cancelled'];
    @endphp

    <x-admin.page-header title="Booking" description="Kelola seluruh pendaftaran & pemesanan paket jamaah." />

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <x-admin.stat-card label="Total Booking" :value="$totalBooking" accent="var(--color-primary)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3-15H8.25A2.25 2.25 0 006 4.75v14.5A2.25 2.25 0 008.25 21.5h7.5a2.25 2.25 0 002.25-2.25V8.5L14.25 3z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Menunggu Aksi" :value="$totalPending" accent="var(--color-warning-ink)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m6-2a10 10 0 11-20 0 10 10 0 0120 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Lunas / Terjadwal" :value="$totalPaid" accent="var(--color-success)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
        <x-admin.stat-card label="Dibatalkan" :value="$totalCancelled" accent="var(--color-danger)">
            <x-slot:icon><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></x-slot:icon>
        </x-admin.stat-card>
    </div>

    <form method="GET" class="admin-toolbar p-4 mb-5 flex flex-col sm:flex-row gap-3" id="booking-filter-form">
        <div class="relative flex-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 absolute left-3.5 top-1/2 -translate-y-1/2 text-[var(--color-ink-soft)]"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode booking / nama jamaah..." class="filter-input pl-10" id="booking-search-input">
        </div>
        <select name="status" onchange="this.form.submit()" class="filter-select sm:w-56">
            <option value="">Semua Status</option>
            @foreach ($statusOptions as $status)
                <option value="{{ $status }}" @selected(request('status') == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
            @endforeach
        </select>
        @if (request('search') || request('status'))
            <a href="{{ route('admin.bookings.index') }}" class="filter-input sm:w-auto flex items-center justify-center gap-1.5 text-[var(--color-ink-soft)] hover:text-[var(--color-danger)] transition-colors cursor-pointer border-dashed">
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
                    <th>Kode Booking</th>
                    <th>Jamaah</th>
                    <th>Paket</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($bookings as $booking)
                    <tr>
                        <td class="font-medium">{{ $booking->booking_code }}</td>
                        <td>{{ $booking->user->name ?? '-' }} <span class="text-[var(--color-ink-soft)]">({{ $booking->total_people }} org)</span></td>
                        <td>{{ $booking->packageSchedule->package->title ?? '-' }}</td>
                        <td>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</td>
                        <td><x-admin.status-badge :status="$booking->status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="action-icon-btn" title="Detail">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            </a>
                        </td>
                    </tr>
                @empty
                @endforelse
            </tbody>
        </table>
        </div>

        @if ($bookings->isEmpty())
            <x-admin.empty-state title="Tidak ada booking ditemukan" description="Coba ubah kata kunci pencarian atau filter status." />
        @endif
    </div>

    {{ $bookings->links('components.admin.pagination') }}

    <script>
        (function () {
            const input = document.getElementById('booking-search-input');
            const form = document.getElementById('booking-filter-form');
            if (!input || !form) return;
            let t;
            input.addEventListener('input', () => {
                clearTimeout(t);
                t = setTimeout(() => form.submit(), 500);
            });
        })();
    </script>

@endsection
