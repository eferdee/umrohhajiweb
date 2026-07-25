@extends('layouts.site')

@section('title', 'Pendaftaran Berhasil')

@section('content')
    <section class="max-w-3xl mx-auto px-5 sm:px-8 py-12 sm:py-16">
        <div class="reveal rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-6 sm:p-10 text-center shadow-sm shadow-black/[0.03]">
            <div class="w-16 h-16 rounded-full bg-[var(--color-success)]/10 text-[var(--color-success)] flex items-center justify-center mx-auto mb-5" style="animation: pulseGlow 2.6s ease-in-out infinite;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-8 h-8">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
            <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">Pendaftaran Diterima</span>
            <h1 class="font-display text-2xl sm:text-3xl mt-2">Terima Kasih, Pendaftaran Berhasil</h1>
            <p class="text-sm text-[var(--color-ink-soft)] mt-3">Kode booking Anda</p>
            <p class="font-display text-xl text-[var(--color-primary)] mt-1">{{ $booking->booking_code }}</p>

            <div class="mt-8 flex items-start gap-2.5 text-left rounded-[var(--radius-card)] border border-[var(--color-warning)]/25 bg-[var(--color-warning)]/8 px-5 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0 text-[var(--color-warning-ink,var(--color-warning))]"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                <div class="text-sm text-[var(--color-warning-ink,var(--color-warning))]">
                    <p class="font-medium">Segera lakukan pembayaran</p>
                    <p class="mt-0.5">Selesaikan pembayaran sebelum <span class="font-medium">{{ $booking->payment_deadline->translatedFormat('d M Y, H:i') }}</span> agar kursi Anda tetap aman.</p>
                </div>
            </div>

            <div class="mt-8 text-left grid grid-cols-1 sm:grid-cols-2 gap-4 border-t border-[var(--color-line)] pt-6">
                <x-site.detail-item label="Paket">{{ $booking->packageSchedule->package->title }}</x-site.detail-item>
                <x-site.detail-item label="Jadwal Keberangkatan">{{ $booking->packageSchedule->departure_date->translatedFormat('d M Y') }} dari {{ $booking->packageSchedule->departure_city }}</x-site.detail-item>
                <x-site.detail-item label="Jumlah Jamaah">{{ $booking->total_people }} orang</x-site.detail-item>
                <div>
                    <span class="detail-item-label">Total Tagihan</span>
                    <span class="text-sm text-[var(--color-primary)] font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                <x-site.detail-item label="Batas Pembayaran">{{ $booking->payment_deadline->translatedFormat('d M Y, H:i') }}</x-site.detail-item>
                <div>
                    <span class="detail-item-label">Status</span>
                    <span class="badge badge-warning">Menunggu Pembayaran</span>
                </div>
            </div>

            <div class="mt-8 text-left">
                <h2 class="font-display text-lg mb-3">Data Jamaah</h2>
                <ul class="text-left text-sm divide-y divide-[var(--color-line)] border border-[var(--color-line)] rounded-[var(--radius-card)] overflow-hidden">
                    @foreach ($booking->pilgrims as $pilgrim)
                        <li class="px-4 py-3.5 flex items-center justify-between gap-3">
                            <span>{{ $pilgrim->full_name }}</span>
                            <span class="badge badge-warning shrink-0">Dokumen: Menunggu Verifikasi</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center justify-center gap-2 mt-8 px-8 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all duration-200">
                Ke Dashboard Saya
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </a>
        </div>
    </section>
@endsection
