@extends('layouts.admin')

@section('title', 'Detail Pesan')

@section('content')

    <a href="{{ route('admin.contacts.index') }}" class="text-sm text-[var(--color-ink-soft)] hover:underline">&larr; Kembali</a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-4">

        <div class="lg:col-span-2 bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <p class="text-xs text-[var(--color-gold-ink)] font-medium mb-1">{{ $contact->created_at->format('d M Y H:i') }}</p>
                    <h2 class="font-display text-xl">{{ $contact->subject }}</h2>
                </div>
                <x-admin.status-badge :status="$contact->status" />
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm mb-6 pb-6 border-b border-[var(--color-line)]">
                <div><p class="text-[var(--color-ink-soft)]">Nama</p><p class="font-medium">{{ $contact->name }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Email</p><p class="font-medium">{{ $contact->email }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Telepon</p><p class="font-medium">{{ $contact->phone ?? '-' }}</p></div>
                <div><p class="text-[var(--color-ink-soft)]">Kode Pelacakan</p><p class="font-medium">{{ $contact->tracking_code }}</p></div>
            </div>

            <div>
                <p class="text-sm font-medium mb-2">Isi Pesan</p>
                <p class="text-sm whitespace-pre-line leading-relaxed">{{ $contact->message }}</p>
            </div>

            @if ($contact->reply_message)
                <div class="mt-6 pt-4 border-t border-[var(--color-line)]">
                    <p class="text-sm font-medium mb-2">Balasan Terakhir ke Jamaah</p>
                    <p class="text-sm whitespace-pre-line leading-relaxed bg-[var(--color-primary)]/5 rounded-lg p-4">{{ $contact->reply_message }}</p>
                    @if ($contact->replied_at)
                        <p class="text-xs text-[var(--color-ink-soft)] mt-2">Dibalas pada {{ $contact->replied_at->format('d M Y H:i') }}</p>
                    @endif
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="bg-[var(--color-surface)] rounded-[var(--radius-card)] border border-[var(--color-line)] p-6">
                <h3 class="font-display text-lg mb-1">Tindak Lanjut</h3>
                <p class="text-xs text-[var(--color-ink-soft)] mb-4">Tulis balasan resmi untuk jamaah. Jika "Kirim email" dicentang, sistem otomatis mengirim balasan ini ke email jamaah begitu disimpan. Jamaah juga bisa melihat balasan ini lewat halaman Cek Status Pesan dengan kode <strong>{{ $contact->tracking_code }}</strong>.</p>

                <form method="POST" action="{{ route('admin.contacts.follow-up', $contact) }}" class="space-y-3">
                    @csrf @method('PATCH')

                    <div>
                        <label class="text-xs font-medium text-[var(--color-ink-soft)] mb-1 block">Status</label>
                        <select name="status" class="admin-input w-full">
                            <option value="new" @selected($contact->status === 'new')>Baru</option>
                            <option value="read" @selected($contact->status === 'read')>Dibaca</option>
                            <option value="replied" @selected($contact->status === 'replied')>Sudah Dibalas</option>
                            <option value="closed" @selected($contact->status === 'closed')>Ditutup</option>
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-[var(--color-ink-soft)] mb-1 block">Balasan untuk Jamaah</label>
                        <textarea name="reply_message" rows="5" placeholder="Tulis balasan resmi di sini. Ini akan dikirim/dilihat oleh jamaah."
                                  class="w-full border border-[var(--color-line)] rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ $contact->reply_message }}</textarea>
                    </div>

                    <label class="flex items-center gap-2 text-xs text-[var(--color-ink-soft)]">
                        <input type="checkbox" name="send_email" value="1" checked class="rounded border-[var(--color-line)]">
                        Kirim email balasan ke jamaah sekarang
                    </label>

                    <div>
                        <label class="text-xs font-medium text-[var(--color-ink-soft)] mb-1 block">Catatan Internal (tidak terlihat jamaah)</label>
                        <textarea name="admin_notes" rows="3" placeholder="Contoh: sudah dihubungi via WA tgl 23/7, menunggu konfirmasi jamaah..."
                                  class="w-full border border-[var(--color-line)] rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/30">{{ $contact->admin_notes }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-[var(--color-primary)] text-white px-4 py-2.5 rounded-lg text-sm font-medium hover:opacity-90">
                        Simpan Tindak Lanjut
                    </button>
                </form>
            </div>

            <form method="POST" action="{{ route('admin.contacts.destroy', $contact) }}" onsubmit="return confirm('Hapus pesan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="w-full text-[var(--color-danger)] text-sm hover:underline">Hapus Pesan</button>
            </form>
        </div>

    </div>

@endsection
