@extends('layouts.site')

@section('title', 'Cek Status Pesan')

@section('content')
    <x-site.hero
        eyebrow="Hubungi Kami"
        title="Cek Status Pesan"
        description="Masukkan kode pelacakan dan email yang Anda gunakan saat mengirim pesan untuk melihat status & balasan admin."
        :crumbs="['Beranda' => url('/'), 'Kontak' => route('contact.index'), 'Cek Status' => null]" />

    <section class="max-w-2xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">

        <div class="reveal bg-[var(--color-surface)] border border-[var(--color-line)] rounded-[var(--radius-card)] p-6 sm:p-8 shadow-sm shadow-black/[0.03] mb-8">
            <form method="POST" action="{{ route('contact.status.check') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="tracking_code" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Kode Pelacakan</label>
                    <input id="tracking_code" type="text" name="tracking_code" value="{{ old('tracking_code') }}" placeholder="MSG-XXXXXXXX"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 @error('tracking_code') border-[var(--color-danger)] @enderror">
                    @error('tracking_code') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="email" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 @error('email') border-[var(--color-danger)] @enderror">
                    @error('email') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.99] transition-all duration-200">
                    Cek Status
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" /></svg>
                </button>
            </form>
        </div>

        @isset($contact)
            <div class="reveal bg-[var(--color-surface)] border border-[var(--color-line)] rounded-[var(--radius-card)] p-6 sm:p-7 shadow-sm shadow-black/[0.03]">
                <div class="flex items-start justify-between gap-4 mb-5 pb-5 border-b border-[var(--color-line)]">
                    <div>
                        <p class="text-xs text-[var(--color-ink-soft)] mb-1">{{ $contact->created_at->format('d M Y H:i') }}</p>
                        <h2 class="font-display text-lg">{{ $contact->subject }}</h2>
                    </div>
                    <x-admin.status-badge :status="$contact->status" />
                </div>

                <p class="text-sm text-[var(--color-ink-soft)] whitespace-pre-line leading-relaxed mb-6">{{ $contact->message }}</p>

                @if ($contact->reply_message)
                    <div class="bg-[var(--color-primary)]/5 border-l-2 border-[var(--color-primary)] rounded-lg p-4">
                        <p class="text-xs font-semibold text-[var(--color-primary)] mb-1.5 flex items-center gap-1.5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" /></svg>
                            Balasan Admin
                        </p>
                        <p class="text-sm whitespace-pre-line leading-relaxed">{{ $contact->reply_message }}</p>
                        @if ($contact->replied_at)
                            <p class="text-xs text-[var(--color-ink-soft)] mt-2.5">Dibalas pada {{ $contact->replied_at->format('d M Y H:i') }}</p>
                        @endif
                    </div>
                @else
                    <div class="flex items-start gap-2.5 bg-[var(--color-warning)]/10 rounded-lg p-4 text-sm text-[var(--color-warning-ink,var(--color-warning))]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Pesan Anda sedang diproses oleh tim kami. Balasan akan muncul di sini begitu admin merespons.</span>
                    </div>
                @endif
            </div>
        @endisset
    </section>
@endsection
