@extends('layouts.site')

@section('title', 'Booking ' . $booking->booking_code)

@section('content')
    <section class="max-w-4xl mx-auto px-5 sm:px-8 py-12 sm:py-16">
        <x-site.back-link :href="route('customer.dashboard')" label="Kembali ke dashboard" />

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
            [$statusLabel, $statusTone] = $statusMap[$booking->status] ?? [$booking->status, 'warning'];

            $docStatusMap = [
                'incomplete' => ['Belum Lengkap', 'danger'],
                'pending' => ['Menunggu Verifikasi', 'warning'],
                'verified' => ['Terverifikasi', 'success'],
            ];
        @endphp

        <div class="reveal flex flex-wrap items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-xs uppercase tracking-wide text-[var(--color-primary)]">Detail Booking</span>
                <h1 class="font-display text-2xl sm:text-3xl mt-1.5">{{ $booking->booking_code }}</h1>
            </div>
            <div class="flex items-center gap-3">
                <span class="badge badge-{{ $statusTone }}">{{ $statusLabel }}</span>
                @if ($sisaTagihan > 0 && !in_array($booking->status, ['cancelled', 'paid', 'completed', 'scheduled']))
                    <a href="{{ route('customer.payments.create', $booking) }}" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-full bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 transition-all duration-200">
                        Bayar Sekarang
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                    </a>
                @endif
            </div>
        </div>

        @php
            $incompletePilgrims = $booking->pilgrims->where('document_status', 'incomplete');
            $latestPayment = $booking->payments->first();
            $rejectedPayment = $latestPayment && $latestPayment->status === 'rejected' ? $latestPayment : null;
        @endphp

        @if ($incompletePilgrims->isNotEmpty() || $rejectedPayment)
            <div class="reveal mt-6 rounded-[var(--radius-card)] border border-[var(--color-danger)]/25 bg-[var(--color-danger)]/[0.06] p-5 sm:p-6 space-y-4">
                <div class="flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[var(--color-danger)] shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                    <h2 class="font-display text-lg text-[var(--color-danger)]">Perlu Tindakan Anda</h2>
                </div>

                @foreach ($incompletePilgrims as $pilgrim)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-[12px] bg-[var(--color-surface)] border border-[var(--color-danger)]/15 p-4">
                        <div class="min-w-0">
                            <p class="text-sm font-medium">Dokumen {{ $pilgrim->full_name }} belum lengkap</p>
                            <p class="text-xs text-[var(--color-ink-soft)] mt-1 leading-relaxed">{{ $pilgrim->document_note ?: 'Ada dokumen yang perlu diperbaiki.' }}</p>
                        </div>
                        <a href="{{ route('customer.pilgrims.documents.edit', $pilgrim) }}"
                            class="shrink-0 inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-full bg-[var(--color-danger)] text-white text-xs font-medium hover:brightness-105 transition-all duration-200">
                            Perbaiki Dokumen
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                        </a>
                    </div>
                @endforeach

                @if ($rejectedPayment)
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-[12px] bg-[var(--color-surface)] border border-[var(--color-danger)]/15 p-4">
                        <div class="min-w-0">
                            <p class="text-sm font-medium">Bukti pembayaran terakhir ditolak</p>
                            <p class="text-xs text-[var(--color-ink-soft)] mt-1 leading-relaxed">{{ $rejectedPayment->notes ?: 'Silakan unggah ulang bukti transfer yang jelas dan sesuai.' }}</p>
                        </div>
                        @if ($sisaTagihan > 0)
                            <a href="{{ route('customer.payments.create', $booking) }}"
                                class="shrink-0 inline-flex items-center justify-center gap-1.5 px-5 py-2 rounded-full bg-[var(--color-danger)] text-white text-xs font-medium hover:brightness-105 transition-all duration-200">
                                Kirim Ulang Bukti Bayar
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        @endif

        <div class="reveal mt-6 detail-card p-5 sm:p-6 grid grid-cols-2 sm:grid-cols-3 gap-4">
            <x-site.detail-item label="Paket">{{ $booking->packageSchedule->package->title }}</x-site.detail-item>
            <x-site.detail-item label="Jadwal">{{ $booking->packageSchedule->departure_date->translatedFormat('d M Y') }} dari {{ $booking->packageSchedule->departure_city }}</x-site.detail-item>
            <x-site.detail-item label="Jumlah Jamaah">{{ $booking->total_people }} orang</x-site.detail-item>
            <div>
                <span class="detail-item-label">Total Tagihan</span>
                <span class="text-sm text-[var(--color-primary)] font-semibold">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            <div>
                <span class="detail-item-label">Sisa Tagihan</span>
                <span class="text-sm font-semibold {{ $sisaTagihan > 0 ? 'text-[var(--color-danger)]' : 'text-[var(--color-success)]' }}">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</span>
            </div>
            @if ($booking->payment_deadline)
                <x-site.detail-item label="Batas Pembayaran">{{ $booking->payment_deadline->translatedFormat('d M Y, H:i') }}</x-site.detail-item>
            @endif
            <x-site.detail-item label="Tanggal Booking">{{ $booking->booking_date->translatedFormat('d M Y') }}</x-site.detail-item>
            @if ($booking->notes)
                <x-site.detail-item label="Catatan" :span="true">{{ $booking->notes }}</x-site.detail-item>
            @endif
        </div>

        {{-- ============ DATA JAMAAH ============ --}}
        <div class="reveal mt-10">
            <x-site.section-title eyebrow="Peserta" title="Data Jamaah" align="left" />
            <div class="space-y-3">
                @foreach ($booking->pilgrims as $pilgrim)
                    @php [$docLabel, $docTone] = $docStatusMap[$pilgrim->document_status] ?? [$pilgrim->document_status, 'warning']; @endphp
                    <div class="rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-4 sm:p-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-medium">{{ $pilgrim->full_name }}</p>
                            <p class="text-xs text-[var(--color-ink-soft)] mt-0.5">NIK {{ $pilgrim->nik }} &middot; {{ $pilgrim->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <span class="badge badge-{{ $docTone }}">Dokumen: {{ $docLabel }}</span>
                            @if ($pilgrim->document_status === 'incomplete')
                                <a href="{{ route('customer.pilgrims.documents.edit', $pilgrim) }}" class="text-xs font-medium text-[var(--color-danger)] hover:underline">Perbaiki</a>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ============ RIWAYAT PEMBAYARAN ============ --}}
        <div class="reveal mt-10">
            <x-site.section-title eyebrow="Transaksi" title="Riwayat Pembayaran" align="left" />
            @if ($booking->payments->isEmpty())
                <div class="rounded-[var(--radius-card)] border border-dashed border-[var(--color-line)] p-6 text-sm text-[var(--color-ink-soft)] text-center">
                    Belum ada pembayaran yang tercatat untuk booking ini.
                </div>
            @else
                <div class="divide-y divide-[var(--color-line)] border border-[var(--color-line)] rounded-[var(--radius-card)] overflow-hidden">
                    @php $paymentToneMap = ['verified' => 'success', 'rejected' => 'danger', 'refunded' => 'neutral']; @endphp
                    @foreach ($booking->payments as $payment)
                        <div class="p-4 sm:p-5 bg-[var(--color-surface)]">
                            <div class="flex items-center justify-between gap-4 text-sm">
                                <div>
                                    <p class="font-medium">{{ $payment->invoice_number }}</p>
                                    <p class="text-xs text-[var(--color-ink-soft)] mt-0.5">
                                        {{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}
                                        @if ($payment->payment_date)
                                            &middot; {{ $payment->payment_date->translatedFormat('d M Y') }}
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-[var(--color-primary)] font-medium">Rp {{ number_format($payment->amount, 0, ',', '.') }}</p>
                                    <span class="badge badge-{{ $paymentToneMap[$payment->status] ?? 'warning' }} mt-1">{{ ucfirst(str_replace('_', ' ', $payment->status)) }}</span>
                                </div>
                            </div>
                            @if ($payment->status === 'rejected' && $payment->notes)
                                <p class="text-xs text-[var(--color-danger)] mt-2.5 pt-2.5 border-t border-[var(--color-line)]">Alasan ditolak: {{ $payment->notes }}</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endsection
