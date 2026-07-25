@extends('layouts.admin')

@section('title', 'Edit Booking')

@section('content')

    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali ke detail booking</a>

    <div class="max-w-2xl mt-4">

        <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
            <p class="text-xs text-[var(--color-gold-ink)] font-medium">{{ $booking->booking_code }}</p>
            <h2 class="font-display text-xl mt-1 mb-1">{{ $booking->packageSchedule->package->title ?? '-' }}</h2>
            <p class="text-sm text-[var(--color-ink-soft)] mb-4">Pemesan: {{ $booking->user->name ?? '-' }}</p>

            <div class="rounded-lg bg-[var(--color-paper)] border border-[var(--color-line)] px-4 py-3 mb-6 text-xs text-[var(--color-ink-soft)] leading-relaxed">
                Form ini cuma untuk mengubah <span class="font-medium text-[var(--color-ink)]">total harga</span>, <span class="font-medium text-[var(--color-ink)]">batas waktu pembayaran</span>, dan <span class="font-medium text-[var(--color-ink)]">catatan internal</span>.
                Jumlah jamaah dan jadwal keberangkatan tidak bisa diubah dari sini karena terkait langsung dengan kuota kursi &mdash; gunakan panel "Ubah Status" di halaman detail untuk itu.
            </div>

            @if ($errors->any())
                <div class="mb-5 rounded-lg border border-[var(--color-danger)]/30 bg-[var(--color-danger)]/10 text-[var(--color-danger)] text-sm px-4 py-3">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.bookings.update', $booking) }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-xs text-[var(--color-ink-soft)] mb-1">Total Harga (Rp)</label>
                    <input type="number" name="total_price" min="{{ (int) $booking->totalTerverifikasi() }}" step="1"
                        value="{{ old('total_price', (int) $booking->total_price) }}" required
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-line)] text-sm">
                    <p class="text-[11px] text-[var(--color-ink-soft)] mt-1">
                        Tidak bisa lebih kecil dari nominal yang sudah terverifikasi (Rp {{ number_format($booking->totalTerverifikasi(), 0, ',', '.') }}).
                    </p>
                </div>

                <div>
                    <label class="block text-xs text-[var(--color-ink-soft)] mb-1">Batas Waktu Pembayaran</label>
                    <input type="datetime-local" name="payment_deadline"
                        value="{{ old('payment_deadline', $booking->payment_deadline?->format('Y-m-d\TH:i')) }}"
                        class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-line)] text-sm">
                </div>

                <div>
                    <label class="block text-xs text-[var(--color-ink-soft)] mb-1">Catatan</label>
                    <textarea name="notes" rows="4" class="w-full px-4 py-2.5 rounded-lg border border-[var(--color-line)] text-sm">{{ old('notes', $booking->notes) }}</textarea>
                </div>

                <div class="flex items-center gap-3 pt-2">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[var(--color-primary)] text-white text-sm font-medium hover:bg-[var(--color-primary-dark)] transition">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.bookings.show', $booking) }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">Batal</a>
                </div>
            </form>
        </div>
    </div>

@endsection
