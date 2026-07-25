@extends('layouts.admin')

@section('title', 'Detail Booking')

@section('content')

    <a href="{{ route('admin.bookings.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali ke Booking</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

        <div class="lg:col-span-2 space-y-6">

            {{-- Info booking --}}
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <p class="text-xs text-[var(--color-gold-ink)] font-medium">{{ $booking->booking_code }}</p>
                        <h2 class="font-display text-xl mt-1">{{ $booking->packageSchedule->package->title ?? '-' }}</h2>
                        <p class="text-sm text-[var(--color-ink-soft)] mt-1">
                            {{ $booking->packageSchedule->departure_city ?? '-' }} &middot;
                            {{ $booking->packageSchedule?->departure_date?->format('d M Y') }}
                        </p>
                    </div>
                    @if (!in_array($booking->status, ['completed', 'cancelled']))
                        <div class="shrink-0 text-right">
                            <a href="{{ route('admin.bookings.edit', $booking) }}" class="inline-block px-4 py-2 rounded-lg border border-[var(--color-line)] text-sm hover:bg-[var(--color-paper)] transition">
                                Ubah Harga &amp; Batas Bayar
                            </a>
                            <p class="text-[11px] text-[var(--color-ink-soft)] mt-1.5 max-w-[12rem]">Bukan data jamaah/jadwal. Untuk itu buka tab terkait di bawah.</p>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm pt-4 border-t border-[var(--color-line)]">
                    <div><p class="text-[var(--color-ink-soft)]">Pemesan</p><p class="font-medium">{{ $booking->user->name ?? '-' }}</p></div>
                    <div><p class="text-[var(--color-ink-soft)]">Jumlah Jamaah</p><p class="font-medium">{{ $booking->total_people }} orang</p></div>
                    <div><p class="text-[var(--color-ink-soft)]">Total Harga</p><p class="font-medium">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p></div>
                    <div><p class="text-[var(--color-ink-soft)]">Tanggal Booking</p><p class="font-medium">{{ $booking->booking_date->format('d M Y') }}</p></div>
                    <div><p class="text-[var(--color-ink-soft)]">Batas Bayar</p><p class="font-medium">{{ $booking->payment_deadline?->format('d M Y H:i') ?? '-' }}</p></div>
                </div>

                @if ($booking->notes)
                    <div class="mt-4 pt-4 border-t border-[var(--color-line)]">
                        <p class="text-[var(--color-ink-soft)] text-sm mb-1">Catatan</p>
                        <p class="text-sm">{{ $booking->notes }}</p>
                    </div>
                @endif
            </div>

            {{-- Ringkasan pembayaran --}}
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 grid grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-[var(--color-ink-soft)] text-xs uppercase tracking-wide">Total Harga</p>
                    <p class="font-medium mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[var(--color-ink-soft)] text-xs uppercase tracking-wide">Terverifikasi</p>
                    <p class="font-medium mt-1 text-[var(--color-success)]">Rp {{ number_format($booking->totalTerverifikasi(), 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-[var(--color-ink-soft)] text-xs uppercase tracking-wide">Sisa Tagihan</p>
                    <p class="font-medium mt-1 {{ $booking->sisaTagihan() > 0 ? 'text-[var(--color-danger)]' : 'text-[var(--color-success)]' }}">
                        Rp {{ number_format($booking->sisaTagihan(), 0, ',', '.') }}
                    </p>
                </div>
            </div>

            {{-- Data jamaah --}}
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] overflow-hidden">
                <div class="px-6 py-4 border-b border-[var(--color-line)]">
                    <h3 class="font-display text-lg">Data Jamaah</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[var(--color-ink-soft)] bg-[var(--color-paper)]">
                            <th class="py-2.5 px-6 font-medium">Nama</th>
                            <th class="py-2.5 px-6 font-medium">NIK</th>
                            <th class="py-2.5 px-6 font-medium">Dokumen</th>
                            <th class="py-2.5 px-6 font-medium text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-line)]">
                        @forelse ($booking->pilgrims as $pilgrim)
                            @php
                                $docColor = match ($pilgrim->document_status) {
                                    'verified' => 'bg-[var(--color-success)]/10 text-[var(--color-success)]',
                                    'pending' => 'bg-[var(--color-warning)]/10 text-[var(--color-warning)]',
                                    default => 'bg-[var(--color-danger)]/10 text-[var(--color-danger)]',
                                };
                            @endphp
                            <tr>
                                <td class="py-3 px-6">{{ $pilgrim->full_name }}</td>
                                <td class="py-3 px-6 text-[var(--color-ink-soft)]">{{ $pilgrim->nik }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $docColor }}">{{ ucfirst($pilgrim->document_status) }}</span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <a href="{{ route('admin.pilgrims.show', $pilgrim) }}" class="text-[var(--color-primary)] hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 px-6 text-center text-[var(--color-ink-soft)]">Belum ada data jamaah diisi.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

            {{-- Riwayat pembayaran --}}
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] overflow-hidden">
                <div class="px-6 py-4 border-b border-[var(--color-line)]">
                    <h3 class="font-display text-lg">Riwayat Pembayaran</h3>
                </div>
                <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-[var(--color-ink-soft)] bg-[var(--color-paper)]">
                            <th class="py-2.5 px-6 font-medium">Invoice</th>
                            <th class="py-2.5 px-6 font-medium">Jumlah</th>
                            <th class="py-2.5 px-6 font-medium">Status</th>
                            <th class="py-2.5 px-6 font-medium text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[var(--color-line)]">
                        @forelse ($booking->payments as $payment)
                            @php
                                $payColor = match ($payment->status) {
                                    'verified' => 'bg-[var(--color-success)]/10 text-[var(--color-success)]',
                                    'rejected' => 'bg-[var(--color-danger)]/10 text-[var(--color-danger)]',
                                    default => 'bg-[var(--color-warning)]/10 text-[var(--color-warning)]',
                                };
                            @endphp
                            <tr>
                                <td class="py-3 px-6">{{ $payment->invoice_number }}</td>
                                <td class="py-3 px-6">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                                <td class="py-3 px-6">
                                    <span class="px-2.5 py-1 rounded-full text-xs font-medium {{ $payColor }}">{{ ucfirst($payment->status) }}</span>
                                </td>
                                <td class="py-3 px-6 text-right">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="text-[var(--color-primary)] hover:underline">Lihat</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 px-6 text-center text-[var(--color-ink-soft)]">Belum ada pembayaran masuk.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>

        </div>

        {{-- Sidebar aksi --}}
        <div class="space-y-6">
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                <h3 class="font-display text-lg mb-4">Ubah Status</h3>
                <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="space-y-3">
                    @csrf @method('PATCH')
                    <select name="status" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                        @foreach (['pending', 'waiting_payment', 'waiting_verification', 'partially_paid', 'paid', 'scheduled', 'completed', 'cancelled'] as $status)
                            <option value="{{ $status }}" @selected($booking->status == $status)>{{ ucfirst(str_replace('_', ' ', $status)) }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">
                        Simpan Status
                    </button>
                </form>
            </div>

            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                <h3 class="font-display text-lg mb-2 text-[var(--color-danger)]">Hapus Booking</h3>
                @if (in_array($booking->status, ['pending', 'cancelled']))
                    <p class="text-xs text-[var(--color-ink-soft)] mb-4">Hanya bisa dihapus jika status pending/cancelled.</p>
                    <form method="POST" action="{{ route('admin.bookings.destroy', $booking) }}" onsubmit="return confirm('Hapus data booking ini secara permanen?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full border border-[var(--color-danger)] text-[var(--color-danger)] px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-danger)]/5">
                            Hapus Booking
                        </button>
                    </form>
                @else
                    <p class="text-xs text-[var(--color-ink-soft)]">
                        Booking dengan status <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $booking->status)) }}</span> tidak bisa dihapus.
                        Ubah status ke <span class="font-medium">Cancelled</span> lewat panel "Ubah Status" di atas dulu kalau memang perlu dihapus.
                    </p>
                @endif
            </div>
        </div>

    </div>

@endsection
