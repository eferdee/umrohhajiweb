@extends('layouts.site')

@section('title', 'Dashboard Saya')

@section('content')
    <section class="max-w-5xl mx-auto px-5 sm:px-8 py-12 sm:py-16">
        <div class="flex items-center justify-between flex-wrap gap-4 mb-8">
            <div>
                <span class="text-xs uppercase tracking-wide text-[var(--color-primary)]">Dashboard Saya</span>
                <h1 class="font-display text-2xl sm:text-3xl mt-2">Riwayat Booking</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('packages.index') }}" class="px-5 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm hover:bg-[var(--color-primary-dark)] transition">
                    Daftar Paket Baru
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-full border border-[var(--color-line)] text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition">
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        @php
            $statusMap = [
                'pending' => ['Menunggu Diproses', 'warning'],
                'waiting_payment' => ['Menunggu Pembayaran', 'warning'],
                'waiting_verification' => ['Menunggu Verifikasi', 'warning'],
                'partially_paid' => ['Sebagian Lunas', 'warning'],
                'paid' => ['Lunas', 'success'],
                'scheduled' => ['Terjadwal', 'success'],
                'completed' => ['Selesai', 'success'],
                'cancelled' => ['Dibatalkan', 'danger'],
            ];
        @endphp

        @if ($bookings->isEmpty())
            <x-site.empty-state
                title="Anda belum memiliki booking"
                description="Yuk mulai perjalanan ibadah Anda dengan memilih salah satu paket kami.">
                <x-slot name="action">
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-1.5 px-6 py-2.5 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] transition-colors duration-200">
                        Lihat Paket
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </a>
                </x-slot>
            </x-site.empty-state>
        @else
            <div class="space-y-4">
                @foreach ($bookings as $booking)
                    @php
                        [$label, $tone] = $statusMap[$booking->status] ?? [$booking->status, 'warning'];
                        $needsAction = $booking->pilgrims->contains('document_status', 'incomplete')
                            || optional($booking->latestPayment)->status === 'rejected';
                    @endphp
                    <a href="{{ route('customer.bookings.show', $booking) }}"
                        class="block rounded-[var(--radius-card)] border {{ $needsAction ? 'border-[var(--color-danger)]/30' : 'border-[var(--color-line)]' }} bg-[var(--color-surface)] p-5 sm:p-6 hover:shadow-lg transition">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                            <div>
                                <p class="text-xs text-[var(--color-ink-soft)]">{{ $booking->booking_code }}</p>
                                <h2 class="font-display text-lg mt-1">{{ $booking->packageSchedule->package->title }}</h2>
                                <p class="text-sm text-[var(--color-ink-soft)] mt-1">
                                    {{ $booking->packageSchedule->departure_date->translatedFormat('d M Y') }}
                                    dari {{ $booking->packageSchedule->departure_city }}
                                    &middot; {{ $booking->total_people }} jamaah
                                </p>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                                @if ($needsAction)
                                    <span class="badge badge-danger">Perlu Tindakan</span>
                                @endif
                                <span class="text-sm text-[var(--color-primary)] font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                                <span class="badge badge-{{ $tone }}">{{ $label }}</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $bookings->links('vendor.pagination.site') }}
            </div>
        @endif
    </section>
@endsection
