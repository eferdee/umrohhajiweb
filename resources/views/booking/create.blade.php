@extends('layouts.site')

@section('title', 'Pendaftaran — ' . $schedule->package->title)

@section('content')
    <x-site.hero
        eyebrow="Formulir Pendaftaran"
        :title="$schedule->package->title"
        description="Lengkapi data jamaah untuk menyelesaikan pendaftaran pada jadwal keberangkatan ini."
        :crumbs="['Beranda' => url('/'), 'Paket' => route('packages.index'), $schedule->package->title => route('packages.show', $schedule->package), 'Daftar' => null]" />

    <section class="max-w-4xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">

        <x-site.back-link :href="route('packages.show', $schedule->package)" label="Kembali ke detail paket" />

        {{-- ============ STEP INDICATOR ============ --}}
        <div class="reveal mt-6 flex items-center" aria-hidden="true">
            <div class="site-step">
                <span class="site-step-dot is-done">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </span>
                <span class="site-step-label">Jadwal</span>
            </div>
            <span class="site-step-line is-done"></span>
            <div class="site-step">
                <span class="site-step-dot is-active">2</span>
                <span class="site-step-label is-active">Data Jamaah</span>
            </div>
            <span class="site-step-line"></span>
            <div class="site-step">
                <span class="site-step-dot">3</span>
                <span class="site-step-label">Kirim &amp; Bayar</span>
            </div>
        </div>

        {{-- ============ RINGKASAN JADWAL ============ --}}
        <div class="reveal detail-card mt-5 p-5 sm:p-6 grid grid-cols-2 sm:grid-cols-4 gap-4">
            <x-site.detail-item label="Paket">{{ $schedule->package->title }}</x-site.detail-item>
            <x-site.detail-item label="Berangkat dari">{{ $schedule->departure_city }}</x-site.detail-item>
            <x-site.detail-item label="Tanggal">{{ $schedule->departure_date->translatedFormat('d M Y') }}</x-site.detail-item>
            <div>
                <span class="detail-item-label">Harga / Jamaah</span>
                <span class="text-sm text-[var(--color-primary)] font-semibold">Rp {{ number_format($schedule->price, 0, ',', '.') }}</span>
            </div>
        </div>

        @if ($errors->any())
            <div class="reveal mt-6 flex items-start gap-2.5 rounded-[var(--radius-card)] border border-[var(--color-danger)]/25 bg-[var(--color-danger)]/8 text-[var(--color-danger)] text-sm px-5 py-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008zM21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <div>
                    <p class="font-medium mb-1.5">Periksa kembali isian Anda:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form method="POST" action="{{ route('booking.store', $schedule) }}" enctype="multipart/form-data" class="mt-8"
            x-data="{
                pilgrims: {{ Js::from(old('pilgrims') ?: [(object) []]) }},
                maxSeat: {{ (int) $schedule->available_seat }},
                touched: {},

                add() { if (this.pilgrims.length < this.maxSeat) this.pilgrims.push({}) },
                remove(i) {
                    if (this.pilgrims.length <= 1) return;
                    this.pilgrims.splice(i, 1);
                    const next = {};
                    Object.keys(this.touched).forEach((key) => {
                        const [idx, field] = key.split('.');
                        const n = Number(idx);
                        if (n === i) return;
                        next[(n > i ? n - 1 : n) + '.' + field] = this.touched[key];
                    });
                    this.touched = next;
                },
                touch(i, field) { this.touched[i + '.' + field] = true },
                isTouched(i, field) { return !!this.touched[i + '.' + field] },
                err(i, field) {
                    const p = this.pilgrims[i] || {};
                    switch (field) {
                        case 'full_name': return !p.full_name ? 'Nama lengkap wajib diisi.' : '';
                        case 'gender': return !p.gender ? 'Pilih jenis kelamin.' : '';
                        case 'nik':
                            if (!p.nik) return 'NIK wajib diisi.';
                            if (!/^\d{16}$/.test(p.nik)) return 'NIK harus 16 digit angka.';
                            if (this.pilgrims.some((x, xi) => xi !== i && x.nik && x.nik === p.nik)) return 'NIK sama dengan jamaah lain.';
                            return '';
                        case 'birth_place': return !p.birth_place ? 'Tempat lahir wajib diisi.' : '';
                        case 'birth_date': return !p.birth_date ? 'Tanggal lahir wajib diisi.' : '';
                        case 'phone': {
                            if (!p.phone) return 'No. HP wajib diisi.';
                            const digits = String(p.phone).replace(/[\s-]/g, '');
                            if (!/^(\+62|62|0)8[1-9][0-9]{6,10}$/.test(digits)) return 'Format nomor HP Indonesia tidak valid (mis. 08123456789).';
                            return '';
                        }
                        case 'passport_expired': {
                            if (!p.passport_expired) return '';
                            const t = new Date(); t.setHours(0, 0, 0, 0);
                            return new Date(p.passport_expired) <= t ? 'Masa berlaku paspor harus setelah hari ini.' : '';
                        }
                        case 'address': return !p.address ? 'Alamat wajib diisi.' : '';
                        default: return '';
                    }
                },
                hasError(i, field) { return this.isTouched(i, field) && !!this.err(i, field) },
                onSubmit(e) {
                    const requiredFields = ['full_name', 'gender', 'nik', 'birth_place', 'birth_date', 'phone', 'address'];
                    let firstInvalid = null;
                    this.pilgrims.forEach((p, i) => {
                        requiredFields.forEach((f) => {
                            this.touch(i, f);
                            if (!firstInvalid && this.err(i, f)) firstInvalid = 'pilgrim-' + i;
                        });
                    });
                    if (firstInvalid) {
                        e.preventDefault();
                        this.$nextTick(() => {
                            document.getElementById(firstInvalid)?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        });
                    }
                },
            }"
            @submit="onSubmit($event)">
            @csrf

            <template x-for="(p, index) in pilgrims" :key="index">
                <div :id="'pilgrim-' + index"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 -translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 -translate-y-2"
                    class="mt-6 rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-5 sm:p-7 shadow-sm shadow-black/[0.03] scroll-mt-24">

                    <div class="flex items-center justify-between mb-6 pb-5 border-b border-[var(--color-line)]">
                        <div class="flex items-center gap-3">
                            <span class="shrink-0 w-8 h-8 rounded-full bg-[var(--color-primary)]/10 text-[var(--color-primary)] flex items-center justify-center text-xs font-display" x-text="String(index + 1).padStart(2, '0')"></span>
                            <h2 class="font-display text-lg">Data Jamaah <span x-text="index + 1"></span></h2>
                        </div>
                        <button type="button" x-show="pilgrims.length > 1" x-cloak @click="remove(index)" class="inline-flex items-center gap-1 text-xs font-medium text-[var(--color-danger)] hover:underline">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            Hapus
                        </button>
                    </div>

                    {{-- ---- Sub-bagian: Data Diri ---- --}}
                    <div class="flex items-center gap-2 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Data Diri</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="sm:col-span-2">
                            <label class="site-field-label">Nama Lengkap (sesuai KTP) <span class="text-[var(--color-danger)]">*</span></label>
                            <input type="text" :name="`pilgrims[${index}][full_name]`" x-model="p.full_name" required
                                @blur="touch(index, 'full_name')" :class="hasError(index, 'full_name') && 'is-invalid'"
                                class="site-field-input">
                            <p class="site-field-error" x-show="hasError(index, 'full_name')" x-cloak x-text="err(index, 'full_name')"></p>
                        </div>

                        <div>
                            <label class="site-field-label">Jenis Kelamin <span class="text-[var(--color-danger)]">*</span></label>
                            <select :name="`pilgrims[${index}][gender]`" x-model="p.gender" required
                                @blur="touch(index, 'gender')" :class="hasError(index, 'gender') && 'is-invalid'"
                                class="site-field-select">
                                <option value="">Pilih</option>
                                <option value="male">Laki-laki</option>
                                <option value="female">Perempuan</option>
                            </select>
                            <p class="site-field-error" x-show="hasError(index, 'gender')" x-cloak x-text="err(index, 'gender')"></p>
                        </div>

                        <div>
                            <label class="site-field-label">NIK (16 digit) <span class="text-[var(--color-danger)]">*</span></label>
                            <input type="text" :name="`pilgrims[${index}][nik]`" x-model="p.nik" required maxlength="16" pattern="\d{16}" inputmode="numeric"
                                @blur="touch(index, 'nik')" :class="hasError(index, 'nik') && 'is-invalid'"
                                class="site-field-input">
                            <p class="site-field-error" x-show="hasError(index, 'nik')" x-cloak x-text="err(index, 'nik')"></p>
                        </div>

                        <div>
                            <label class="site-field-label">Tempat Lahir <span class="text-[var(--color-danger)]">*</span></label>
                            <input type="text" :name="`pilgrims[${index}][birth_place]`" x-model="p.birth_place" required
                                @blur="touch(index, 'birth_place')" :class="hasError(index, 'birth_place') && 'is-invalid'"
                                class="site-field-input">
                            <p class="site-field-error" x-show="hasError(index, 'birth_place')" x-cloak x-text="err(index, 'birth_place')"></p>
                        </div>

                        <x-site.date-field
                            label="Tanggal Lahir"
                            model="p.birth_date"
                            name="`pilgrims[${index}][birth_date]`"
                            :required="true"
                            :min-year="1900"
                            :max-year="now()->year"
                            :max-date="now()->subDay()->toDateString()"
                            :default-year="now()->subYears(30)->year"
                            error-text="hasError(index, 'birth_date') && err(index, 'birth_date')" />

                        <div>
                            <label class="site-field-label">Nomor Paspor (jika ada)</label>
                            <input type="text" :name="`pilgrims[${index}][passport_number]`" x-model="p.passport_number"
                                class="site-field-input">
                        </div>

                        <x-site.date-field
                            label="Masa Berlaku Paspor"
                            model="p.passport_expired"
                            name="`pilgrims[${index}][passport_expired]`"
                            :min-year="now()->year"
                            :max-year="now()->year + 20"
                            :min-date="now()->addDay()->toDateString()"
                            :default-year="now()->addYears(2)->year"
                            error-text="hasError(index, 'passport_expired') && err(index, 'passport_expired')" />
                    </div>

                    {{-- ---- Sub-bagian: Kontak & Alamat ---- --}}
                    <div class="flex items-center gap-2 mt-7 mb-4 pt-6 border-t border-[var(--color-line)]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                        <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Kontak &amp; Alamat</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="site-field-label">No. HP <span class="text-[var(--color-danger)]">*</span></label>
                            <input type="tel" :name="`pilgrims[${index}][phone]`" x-model="p.phone" required
                                inputmode="numeric" placeholder="mis. 08123456789"
                                @blur="touch(index, 'phone')" :class="hasError(index, 'phone') && 'is-invalid'"
                                class="site-field-input">
                            <p class="site-field-error" x-show="hasError(index, 'phone')" x-cloak x-text="err(index, 'phone')"></p>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="site-field-label">Alamat Lengkap <span class="text-[var(--color-danger)]">*</span></label>
                            <textarea :name="`pilgrims[${index}][address]`" x-model="p.address" required rows="2"
                                @blur="touch(index, 'address')" :class="hasError(index, 'address') && 'is-invalid'"
                                class="site-field-textarea"></textarea>
                            <p class="site-field-error" x-show="hasError(index, 'address')" x-cloak x-text="err(index, 'address')"></p>
                        </div>

                        <div>
                            <label class="site-field-label">Kontak Darurat (nama &amp; no. HP)</label>
                            <input type="text" :name="`pilgrims[${index}][emergency_contact]`" x-model="p.emergency_contact"
                                class="site-field-input">
                        </div>

                        <div>
                            <label class="site-field-label">Hubungan dengan Kontak Darurat</label>
                            <input type="text" :name="`pilgrims[${index}][relationship]`" x-model="p.relationship" placeholder="mis. Anak, Suami, Saudara"
                                class="site-field-input">
                        </div>
                    </div>

                    {{-- ---- Sub-bagian: Dokumen Pendukung ---- --}}
                    <div class="mt-7 pt-6 border-t border-[var(--color-line)]">
                        <div class="flex items-center gap-2 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Dokumen Pendukung</p>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div x-data="{ fileName: '' }">
                                <label class="site-field-label">Foto KTP <span class="text-[var(--color-danger)]">*</span></label>
                                <input type="file" :name="`pilgrims[${index}][ktp_photo]`" accept="image/*" required
                                    @change="fileName = $event.target.files[0]?.name || ''" class="site-field-file">
                                <p class="text-xs mt-1 text-[var(--color-ink-soft)] truncate" x-show="fileName" x-cloak x-text="fileName"></p>
                            </div>
                            <div x-data="{ fileName: '' }">
                                <label class="site-field-label">Foto Kartu Keluarga <span class="text-[var(--color-danger)]">*</span></label>
                                <input type="file" :name="`pilgrims[${index}][family_card_photo]`" accept="image/*" required
                                    @change="fileName = $event.target.files[0]?.name || ''" class="site-field-file">
                                <p class="text-xs mt-1 text-[var(--color-ink-soft)] truncate" x-show="fileName" x-cloak x-text="fileName"></p>
                            </div>
                            <div x-data="{ fileName: '' }">
                                <label class="site-field-label">Foto Paspor (jika ada)</label>
                                <input type="file" :name="`pilgrims[${index}][passport_photo]`" accept="image/*"
                                    @change="fileName = $event.target.files[0]?.name || ''" class="site-field-file">
                                <p class="text-xs mt-1 text-[var(--color-ink-soft)] truncate" x-show="fileName" x-cloak x-text="fileName"></p>
                            </div>
                            <div x-data="{ fileName: '' }">
                                <label class="site-field-label">Pas Foto</label>
                                <input type="file" :name="`pilgrims[${index}][photo]`" accept="image/*"
                                    @change="fileName = $event.target.files[0]?.name || ''" class="site-field-file">
                                <p class="text-xs mt-1 text-[var(--color-ink-soft)] truncate" x-show="fileName" x-cloak x-text="fileName"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <button type="button" @click="add()" x-show="pilgrims.length < maxSeat" x-cloak
                class="mt-5 inline-flex items-center gap-1.5 px-5 py-2.5 rounded-full border border-[var(--color-primary)] text-[var(--color-primary)] text-sm font-medium hover:bg-[var(--color-primary)]/5 active:scale-[0.98] transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                Tambah Jamaah
            </button>
            <p class="text-xs text-[var(--color-ink-soft)] mt-2">Sisa kursi tersedia: {{ $schedule->available_seat }}</p>

            <div class="mt-6">
                <label class="site-field-label">Catatan Tambahan (opsional)</label>
                <textarea name="notes" rows="3" class="site-field-textarea">{{ old('notes') }}</textarea>
            </div>

            {{-- ============ RINGKASAN TAGIHAN ============ --}}
            <div class="mt-8 rounded-[var(--radius-card)] bg-[var(--color-primary)] text-white p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <span class="text-xs uppercase tracking-wide text-white/60">Total Tagihan</span>
                    <p class="font-display text-xl sm:text-2xl mt-1">
                        Rp <span x-text="new Intl.NumberFormat('id-ID').format(pilgrims.length * {{ (int) $schedule->price }})"></span>
                    </p>
                    <p class="text-xs text-white/60 mt-1"><span x-text="pilgrims.length"></span> jamaah &times; Rp {{ number_format($schedule->price, 0, ',', '.') }}</p>
                </div>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-8 py-3 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] text-sm font-semibold hover:brightness-105 active:scale-[0.98] transition-all duration-200">
                    Kirim Pendaftaran
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
                </button>
            </div>
        </form>
    </section>
@endsection
