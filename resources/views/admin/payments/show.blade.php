@extends('layouts.admin')

@section('title', 'Detail Pembayaran')

@section('content')

    <a href="{{ route('admin.payments.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

        <div class="lg:col-span-2 bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
            <p class="text-xs text-[var(--color-gold-ink)] font-medium mb-1">{{ $payment->invoice_number }}</p>
            <h2 class="font-display text-xl mb-4">Rp {{ number_format($payment->amount, 0, ',', '.') }}</h2>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-[var(--color-ink-soft)]">Pemesan</p><p class="font-medium">{{ $payment->booking->user->name ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Booking</p><p class="font-medium">{{ $payment->booking->booking_code ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Paket</p><p class="font-medium">{{ $payment->booking->packageSchedule->package->title ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Jenis Pembayaran</p><p class="font-medium">{{ ucfirst(str_replace('_', ' ', $payment->payment_type)) }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Metode</p><p class="font-medium">{{ str_replace('_', ' ', $payment->payment_method) }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Tanggal Bayar</p><p class="font-medium">{{ $payment->payment_date?->format('d M Y H:i') ?? '-' }}</p></div>
            </div>

            @if ($payment->verifiedBy)
                <div class="mt-4 pt-4 border-t border-[var(--color-line)] text-sm">
                    <p class="text-[var(--color-ink-soft)]">Diverifikasi oleh</p>
                    <p class="font-medium">{{ $payment->verifiedBy->name }} — {{ $payment->verified_at?->format('d M Y H:i') }}</p>
                </div>
            @endif

            @if ($payment->notes)
                <div class="mt-4 pt-4 border-t border-[var(--color-line)]">
                    <p class="text-[var(--color-ink-soft)] text-sm mb-1">Catatan</p>
                    <p class="text-sm">{{ $payment->notes }}</p>
                </div>
            @endif

            <div class="mt-6 pt-4 border-t border-[var(--color-line)]">
                <p class="text-sm font-medium mb-2">Bukti Transfer</p>
                @if ($payment->transfer_proof)
                    <a href="{{ asset('storage/' . $payment->transfer_proof) }}" target="_blank">
                        <img src="{{ asset('storage/' . $payment->transfer_proof) }}" class="max-w-xs rounded-lg border border-[var(--color-line)]">
                    </a>
                @else
                    <p class="text-sm text-[var(--color-ink-soft)]">Belum ada bukti transfer diunggah.</p>
                @endif
            </div>
        </div>

        <div class="space-y-6">
            @if ($payment->status === 'pending')
                <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                    <h3 class="font-display text-lg mb-4">Verifikasi</h3>

                    <form method="POST" action="{{ route('admin.payments.verify', $payment) }}" class="mb-3">
                        @csrf @method('PATCH')
                        <button type="submit" class="w-full bg-[var(--color-success)] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:opacity-90">
                            Terima Pembayaran
                        </button>
                    </form>

                    <form method="POST" action="{{ route('admin.payments.reject', $payment) }}" class="space-y-2">
                        @csrf @method('PATCH')
                        <textarea name="notes" rows="2" placeholder="Alasan penolakan (opsional)"
                                  class="w-full border border-[var(--color-line)] rounded-lg px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[var(--color-danger)]/30"></textarea>
                        <button type="submit" class="w-full border border-[var(--color-danger)] text-[var(--color-danger)] px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-danger)]/5">
                            Tolak Pembayaran
                        </button>
                    </form>
                </div>
            @else
                <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                    <p class="text-sm text-[var(--color-ink-soft)]">Pembayaran ini sudah berstatus <strong>{{ ucfirst($payment->status) }}</strong>, tidak ada aksi lanjutan.</p>
                </div>
            @endif

            @if ($payment->status !== 'verified')
                <form method="POST" action="{{ route('admin.payments.destroy', $payment) }}" onsubmit="return confirm('Hapus data pembayaran ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full text-[var(--color-danger)] text-sm hover:underline">Hapus Data Pembayaran</button>
                </form>
            @endif
        </div>

    </div>

@endsection
