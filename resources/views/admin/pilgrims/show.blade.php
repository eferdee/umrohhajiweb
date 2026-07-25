@extends('layouts.admin')

@section('title', 'Detail Jamaah')

@section('content')

    <a href="{{ route('admin.pilgrims.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

        <div class="lg:col-span-2 bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
            <h2 class="font-display text-xl mb-4">{{ $pilgrim->full_name }}</h2>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div><p class="text-[var(--color-ink-soft)]">NIK</p><p class="font-medium">{{ $pilgrim->nik }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Jenis Kelamin</p><p class="font-medium">{{ $pilgrim->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Tempat, Tanggal Lahir</p><p class="font-medium">{{ $pilgrim->birth_place }}, {{ $pilgrim->birth_date->format('d M Y') }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">No. Paspor</p><p class="font-medium">{{ $pilgrim->passport_number ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Paspor Berlaku s.d.</p><p class="font-medium">{{ $pilgrim->passport_expired?->format('d M Y') ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">No. HP</p><p class="font-medium">{{ $pilgrim->phone ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Kontak Darurat</p><p class="font-medium">{{ $pilgrim->emergency_contact ?? '-' }} ({{ $pilgrim->relationship ?? '-' }})</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Booking Terkait</p><p class="font-medium">{{ $pilgrim->booking->booking_code ?? '-' }}</p></div>
            </div>

            <div class="mt-4 pt-4 border-t border-[var(--color-line)]">
                <p class="text-[var(--color-ink-soft)] text-sm mb-1">Alamat</p>
                <p class="text-sm">{{ $pilgrim->address }}</p>
            </div>

            <div class="mt-6 pt-4 border-t border-[var(--color-line)]">
                <p class="text-sm font-medium mb-3">Dokumen</p>
                <div class="grid grid-cols-3 gap-3">
                    @foreach (['passport_photo' => 'Paspor', 'ktp_photo' => 'KTP', 'family_card_photo' => 'KK'] as $field => $label)
                        <div class="border border-[var(--color-line)] rounded-lg p-3 text-center">
                            @if ($pilgrim->{$field})
                                <a href="{{ asset('storage/' . $pilgrim->{$field}) }}" target="_blank" class="text-[var(--color-primary)] text-xs hover:underline">Lihat {{ $label }}</a>
                            @else
                                <span class="text-xs text-[var(--color-ink-soft)]">{{ $label }} belum diunggah</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6 h-fit">
            <h3 class="font-display text-lg mb-4">Status Dokumen</h3>
            <form method="POST" action="{{ route('admin.pilgrims.document-status', $pilgrim) }}" class="space-y-3"
                x-data="{ status: '{{ old('document_status', $pilgrim->document_status) }}' }">
                @csrf @method('PATCH')
                <select name="document_status" x-model="status" class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">
                    <option value="incomplete" @selected($pilgrim->document_status == 'incomplete')>Belum Lengkap</option>
                    <option value="pending" @selected($pilgrim->document_status == 'pending')>Menunggu Verifikasi</option>
                    <option value="verified" @selected($pilgrim->document_status == 'verified')>Terverifikasi</option>
                </select>

                <div x-show="status === 'incomplete'" x-cloak>
                    <label class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">
                        Catatan untuk jamaah <span class="text-[var(--color-danger)]">*</span>
                    </label>
                    <textarea name="document_note" rows="3" placeholder="mis. Foto KTP buram, mohon unggah ulang dengan pencahayaan yang lebih terang."
                        class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30 @error('document_note') border-[var(--color-danger)] @enderror">{{ old('document_note', $pilgrim->document_status === 'incomplete' ? $pilgrim->document_note : '') }}</textarea>
                    @error('document_note') <p class="text-[var(--color-danger)] text-xs mt-1">{{ $message }}</p> @enderror
                    <p class="text-xs text-[var(--color-ink-soft)] mt-1.5">Catatan ini akan tampil ke customer beserta tombol untuk unggah ulang dokumen.</p>
                </div>

                <button type="submit" class="w-full bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)]">
                    Simpan Status
                </button>
            </form>

            @if ($pilgrim->document_status === 'incomplete' && $pilgrim->document_note)
                <div class="mt-4 pt-4 border-t border-[var(--color-line)]">
                    <p class="text-xs text-[var(--color-ink-soft)] mb-1">Catatan aktif saat ini</p>
                    <p class="text-sm text-[var(--color-danger)]">{{ $pilgrim->document_note }}</p>
                </div>
            @endif
        </div>

    </div>

@endsection
