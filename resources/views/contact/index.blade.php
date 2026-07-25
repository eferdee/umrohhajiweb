@extends('layouts.site')

@section('title', 'Kontak')

@section('content')
    <x-site.hero
        eyebrow="Hubungi Kami"
        title="Mari Berbincang"
        description="Ada pertanyaan seputar paket atau pendaftaran? Kirim pesan, tim kami akan segera merespons."
        :crumbs="['Beranda' => url('/'), 'Kontak' => null]">
        <a href="{{ route('contact.status') }}" class="inline-flex items-center gap-1.5 text-sm text-[var(--color-gold-soft)] hover:underline mt-4">
            Sudah pernah kirim pesan? Cek status &amp; balasan di sini &rarr;
        </a>
    </x-site.hero>

    <section id="kontak" class="max-w-6xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">
        @php
            $waNumber = isset($settings['contact_phone']) ? preg_replace('/[^0-9]/', '', $settings['contact_phone']) : null;
            if ($waNumber && str_starts_with($waNumber, '0')) {
                $waNumber = '62' . substr($waNumber, 1);
            }
        @endphp

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6 lg:gap-8">

            {{-- Info kontak --}}
            <div class="reveal lg:col-span-2 bg-[var(--color-primary)] rounded-[var(--radius-card)] p-6 sm:p-8 relative overflow-hidden text-white">
                <div class="absolute -right-14 -top-14 w-48 h-48 rounded-full bg-[var(--color-gold)]/15 blur-3xl pointer-events-none"></div>
                <div class="absolute -left-14 -bottom-14 w-48 h-48 rounded-full bg-[var(--color-primary-light)]/30 blur-3xl pointer-events-none"></div>

                <h2 class="relative font-display text-xl">Informasi Kontak</h2>
                <p class="relative text-sm text-white/70 mt-2 leading-relaxed">Hubungi kami langsung melalui salah satu kanal berikut.</p>

                <div class="relative mt-7 space-y-4 text-sm">
                    @if (!empty($settings['contact_phone']))
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h1.5a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                            </span>
                            <div><p class="font-medium">{{ $settings['contact_phone'] }}</p><p class="text-white/60 text-xs mt-0.5">Telepon / WhatsApp</p></div>
                        </div>
                    @endif
                    @if (!empty($settings['contact_email']))
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            </span>
                            <div><p class="font-medium">{{ $settings['contact_email'] }}</p><p class="text-white/60 text-xs mt-0.5">Email</p></div>
                        </div>
                    @endif
                    @if (!empty($settings['contact_address']))
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            </span>
                            <div><p class="font-medium">{{ $settings['contact_address'] }}</p><p class="text-white/60 text-xs mt-0.5">Alamat</p></div>
                        </div>
                    @endif
                    @if (!empty($settings['operational_hours']))
                        <div class="flex items-start gap-3">
                            <span class="w-9 h-9 rounded-full bg-white/10 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </span>
                            <div><p class="font-medium">{{ $settings['operational_hours'] }}</p><p class="text-white/60 text-xs mt-0.5">Jam Operasional</p></div>
                        </div>
                    @endif
                </div>

                @if ($waNumber)
                    <a href="https://wa.me/{{ $waNumber }}" target="_blank" rel="noopener" class="relative inline-flex items-center gap-2 mt-7 px-6 py-3 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] font-semibold text-sm hover:brightness-105 hover:-translate-y-0.5 transition-all duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="w-4 h-4"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.45 1.27 4.9L2 22l5.25-1.38A9.96 9.96 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10z"/></svg>
                        Chat via WhatsApp
                    </a>
                @endif
            </div>

            {{-- Form kontak --}}
            <div class="reveal lg:col-span-3 bg-[var(--color-surface)] border border-[var(--color-line)] rounded-[var(--radius-card)] p-6 sm:p-8 shadow-sm shadow-black/[0.03]">
                <h2 class="font-display text-xl mb-5">Kirim Pesan</h2>

                <form method="POST" action="{{ route('contact.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Nama</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" required
                               class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 @error('name') border-[var(--color-danger)] @enderror">
                        @error('name') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="email" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                   class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 @error('email') border-[var(--color-danger)] @enderror">
                            @error('email') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label for="phone" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">No. HP (opsional)</label>
                            <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                                   class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200">
                        </div>
                    </div>

                    <div>
                        <label for="subject" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Subjek</label>
                        <input id="subject" type="text" name="subject" value="{{ old('subject') }}" required
                               class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 @error('subject') border-[var(--color-danger)] @enderror">
                        @error('subject') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="message" class="block text-xs font-medium text-[var(--color-ink-soft)] mb-1.5">Pesan</label>
                        <textarea id="message" name="message" rows="5" required
                                  class="w-full border border-[var(--color-line)] rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[var(--color-primary)]/25 focus:border-[var(--color-primary)] transition-all duration-200 resize-none @error('message') border-[var(--color-danger)] @enderror">{{ old('message') }}</textarea>
                        @error('message') <p class="text-xs text-[var(--color-danger)] mt-1">{{ $message }}</p> @enderror
                    </div>

                    <button type="submit" class="w-full inline-flex items-center justify-center gap-2 bg-[var(--color-primary)] text-white px-6 py-3 rounded-lg text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.99] transition-all duration-200">
                        Kirim Pesan
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
                    </button>
                </form>
            </div>
        </div>
    </section>
@endsection
