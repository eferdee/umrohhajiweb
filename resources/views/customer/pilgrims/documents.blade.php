@extends('layouts.site')

@section('title', 'Unggah Ulang Dokumen — ' . $pilgrim->full_name)

@section('content')
    <section class="max-w-2xl mx-auto px-5 sm:px-8 py-12 sm:py-16">
        <x-site.back-link :href="route('customer.bookings.show', $pilgrim->booking)" label="Kembali ke detail booking" />

        <div class="reveal flex items-start gap-2.5 rounded-[var(--radius-card)] border border-[var(--color-danger)]/25 bg-[var(--color-danger)]/8 text-[var(--color-danger)] text-sm px-5 py-4">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <div>
                <p class="font-medium mb-1">Catatan dari tim kami untuk {{ $pilgrim->full_name }}:</p>
                <p>{{ $pilgrim->document_note ?: 'Ada dokumen yang perlu diperbaiki. Silakan unggah ulang di bawah ini.' }}</p>
            </div>
        </div>

        <div class="reveal mt-6">
            <span class="text-xs uppercase tracking-wide text-[var(--color-primary)]">Perbaiki Dokumen</span>
            <h1 class="font-display text-2xl sm:text-3xl mt-1.5">Unggah Ulang Dokumen</h1>
            <p class="text-sm text-[var(--color-ink-soft)] mt-2">{{ $pilgrim->full_name }} &middot; Booking {{ $pilgrim->booking->booking_code }} &middot; {{ $pilgrim->booking->packageSchedule->package->title }}</p>
        </div>

        @if ($errors->any())
            <div class="reveal mt-6 flex items-start gap-2.5 rounded-[var(--radius-card)] border border-[var(--color-danger)]/25 bg-[var(--color-danger)]/8 text-[var(--color-danger)] text-sm px-5 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('customer.pilgrims.documents.update', $pilgrim) }}" enctype="multipart/form-data"
            class="reveal mt-8 rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-5 sm:p-7 shadow-sm shadow-black/[0.03] space-y-5">
            @csrf

            <p class="text-xs text-[var(--color-ink-soft)] leading-relaxed">
                Anda tidak perlu mengunggah ulang semua dokumen — cukup dokumen yang bermasalah sesuai catatan di atas.
                Dokumen lain yang sudah pernah dikirim akan tetap dipakai.
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach ([
                    'ktp_photo' => 'Foto KTP',
                    'family_card_photo' => 'Foto Kartu Keluarga',
                    'passport_photo' => 'Foto Paspor',
                    'photo' => 'Pas Foto',
                ] as $field => $label)
                    <div x-data="{ fileName: '' }">
                        <label class="site-field-label">{{ $label }}</label>
                        <p class="text-xs text-[var(--color-ink-soft)] mb-1.5">
                            @if ($pilgrim->{$field})
                                Saat ini: <a href="{{ asset('storage/' . $pilgrim->{$field}) }}" target="_blank" class="text-[var(--color-primary)] hover:underline">lihat file lama</a>
                            @else
                                Belum ada file sebelumnya.
                            @endif
                        </p>
                        <input type="file" name="{{ $field }}" accept="image/*"
                            @change="fileName = $event.target.files[0]?.name || ''" class="site-field-file">
                        <p class="text-xs mt-1 text-[var(--color-primary)] truncate" x-show="fileName" x-cloak x-text="fileName"></p>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.99] transition-all duration-200">
                Kirim Dokumen
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            </button>

            <p class="text-xs text-[var(--color-ink-soft)]">Setelah dikirim, status dokumen akan kembali ke &ldquo;Menunggu Verifikasi&rdquo; sampai tim kami mengeceknya.</p>
        </form>
    </section>
@endsection
