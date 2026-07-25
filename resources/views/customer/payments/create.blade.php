@extends('layouts.site')

@section('title', 'Bayar Booking ' . $booking->booking_code)

@section('content')
    @php
        $sudahDibayar = $booking->total_price - $sisaTagihan;
        $pctPaid = $booking->total_price > 0 ? max(0, min(100, round($sudahDibayar / $booking->total_price * 100))) : 0;
    @endphp

    <x-site.hero
        eyebrow="Pembayaran"
        title="Upload Bukti Pembayaran"
        :description="'Booking ' . $booking->booking_code . ' — ' . $booking->packageSchedule->package->title"
        :crumbs="['Beranda' => url('/'), 'Dashboard' => route('customer.dashboard'), 'Booking ' . $booking->booking_code => route('customer.bookings.show', $booking), 'Bayar' => null]" />

    <section class="max-w-3xl mx-auto px-5 sm:px-8 -mt-8 sm:-mt-10 pb-16 sm:pb-24 relative z-10">

        <x-site.back-link :href="route('customer.bookings.show', $booking)" label="Kembali ke detail booking" />

        {{-- ============ PROGRESS STEP ============ --}}
        <div class="reveal mt-6 flex items-center" aria-hidden="true">
            <div class="site-step">
                <span class="site-step-dot is-done">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                </span>
                <span class="site-step-label">Booking</span>
            </div>
            <span class="site-step-line is-done"></span>
            <div class="site-step">
                <span class="site-step-dot is-active">2</span>
                <span class="site-step-label is-active">Pembayaran</span>
            </div>
            <span class="site-step-line"></span>
            <div class="site-step">
                <span class="site-step-dot">3</span>
                <span class="site-step-label">Verifikasi</span>
            </div>
            <span class="site-step-line"></span>
            <div class="site-step">
                <span class="site-step-dot">4</span>
                <span class="site-step-label">Selesai</span>
            </div>
        </div>

        {{-- ============ RINGKASAN BOOKING ============ --}}
        <div class="reveal detail-card mt-6 p-5 sm:p-6">
            <div class="flex items-center gap-2 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Ringkasan Booking</p>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <x-site.detail-item label="Kode Booking">{{ $booking->booking_code }}</x-site.detail-item>
                <x-site.detail-item label="Paket" :span="true">{{ $booking->packageSchedule->package->title }}</x-site.detail-item>
                <x-site.detail-item label="Berangkat">{{ $booking->packageSchedule->departure_date->translatedFormat('d M Y') }}</x-site.detail-item>
                <div>
                    <span class="detail-item-label">Status</span>
                    @php
                        $statusMap = [
                            'pending' => ['Menunggu Pembayaran', 'warning'],
                            'waiting_verification' => ['Menunggu Verifikasi', 'warning'],
                        ];
                        [$statusLabel, $statusTone] = $statusMap[$booking->status] ?? [ucfirst(str_replace('_', ' ', $booking->status)), 'neutral'];
                    @endphp
                    <span class="badge badge-{{ $statusTone }}">{{ $statusLabel }}</span>
                </div>
            </div>
        </div>

        {{-- ============ INFO TAGIHAN (menonjol) ============ --}}
        <div class="reveal mt-5 rounded-[var(--radius-card)] bg-[var(--color-primary)] text-white p-5 sm:p-7 overflow-hidden relative">
            <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-white/5 blur-2xl"></div>
            <div class="relative grid grid-cols-1 sm:grid-cols-3 gap-5 sm:gap-6">
                <div>
                    <span class="text-xs uppercase tracking-wide text-white/60">Total Tagihan</span>
                    <p class="font-display text-xl sm:text-2xl mt-1">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-xs uppercase tracking-wide text-white/60">Dibayar / Menunggu Verifikasi</span>
                    <p class="font-display text-xl sm:text-2xl mt-1">Rp {{ number_format($sudahDibayar, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-xs uppercase tracking-wide text-[var(--color-gold-soft)]">Sisa Tagihan</span>
                    <p class="font-display text-2xl sm:text-3xl mt-1 text-[var(--color-gold-soft)]">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</p>
                </div>
            </div>
            <div class="relative mt-5 pt-4 border-t border-white/15">
                <div class="flex items-center justify-between text-xs text-white/60 mb-1.5">
                    <span>Progres pembayaran</span>
                    <span>{{ $pctPaid }}%</span>
                </div>
                <div class="h-1.5 rounded-full bg-white/15 overflow-hidden">
                    <div class="h-full rounded-full bg-[var(--color-gold)] transition-[width] duration-500" style="width: {{ $pctPaid }}%"></div>
                </div>
            </div>
        </div>

        {{-- ============ RIWAYAT PEMBAYARAN ============ --}}
        @if ($booking->payments->isNotEmpty())
            <div class="reveal mt-8">
                <div class="flex items-center gap-2 mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Riwayat Pembayaran</p>
                </div>
                <div class="divide-y divide-[var(--color-line)] border border-[var(--color-line)] rounded-[var(--radius-card)] overflow-hidden bg-[var(--color-surface)]">
                    @foreach ($booking->payments as $payment)
                        <div class="p-3.5 sm:p-4 flex items-center justify-between gap-3 text-sm">
                            <div class="min-w-0">
                                <p class="font-medium truncate">{{ $payment->invoice_number }}</p>
                                <p class="text-xs text-[var(--color-ink-soft)] mt-0.5">{{ $payment->payment_date?->translatedFormat('d M Y') }}</p>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                <span class="font-semibold">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="badge badge-{{ $payment->status === 'verified' ? 'success' : ($payment->status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($payment->status) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

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

        {{-- ============ FORM PEMBAYARAN ============ --}}
        <form method="POST" action="{{ route('customer.payments.store', $booking) }}" enctype="multipart/form-data"
            class="reveal mt-8 rounded-[var(--radius-card)] border border-[var(--color-line)] bg-[var(--color-surface)] p-5 sm:p-7 shadow-sm shadow-black/[0.03]"
            x-data="{
                sisaTagihan: {{ (int) $sisaTagihan }},
                paymentType: '{{ old('payment_type') }}',
                amount: '{{ old('amount') }}',
                paymentDate: '{{ old('payment_date', now()->toDateString()) }}',
                fileName: '',
                filePreview: null,
                fileIsImage: false,
                touched: {},

                touch(field) { this.touched[field] = true },
                isTouched(field) { return !!this.touched[field] },
                err(field) {
                    switch (field) {
                        case 'payment_type': return !this.paymentType ? 'Pilih jenis pembayaran.' : '';
                        case 'amount': {
                            const n = Number(this.amount);
                            if (!this.amount) return 'Jumlah transfer wajib diisi.';
                            if (Number.isNaN(n) || n < 100000) return 'Minimal transfer Rp 100.000.';
                            if (n > this.sisaTagihan) return 'Jumlah melebihi sisa tagihan.';
                            return '';
                        }
                        case 'payment_date': return !this.paymentDate ? 'Tanggal transfer wajib diisi.' : '';
                        case 'transfer_proof': return !this.fileName ? 'Bukti transfer wajib diunggah.' : '';
                        default: return '';
                    }
                },
                hasError(field) { return this.isTouched(field) && !!this.err(field) },
                onTypeChange() {
                    if (this.paymentType === 'full_payment') {
                        this.amount = String(this.sisaTagihan);
                    } else if (this.paymentType === 'dp' || this.paymentType === 'installment') {
                        this.amount = '';
                    }
                    this.touch('payment_type');
                },
                onFileChange(e) {
                    const file = e.target.files[0];
                    if (this.filePreview) URL.revokeObjectURL(this.filePreview);
                    this.filePreview = null;
                    if (!file) { this.fileName = ''; this.fileIsImage = false; this.touch('transfer_proof'); return; }
                    this.fileName = file.name;
                    this.fileIsImage = file.type.startsWith('image/');
                    if (this.fileIsImage) this.filePreview = URL.createObjectURL(file);
                    this.touch('transfer_proof');
                },
                onSubmit(e) {
                    ['payment_type', 'amount', 'payment_date', 'transfer_proof'].forEach((f) => this.touch(f));
                    const hasAnyError = ['payment_type', 'amount', 'payment_date', 'transfer_proof'].some((f) => this.err(f));
                    if (hasAnyError) e.preventDefault();
                },
            }"
            @submit="onSubmit($event)">
            @csrf

            <div class="flex items-center gap-2 mb-5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-4 h-4 text-[var(--color-primary)]"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-9-9v9a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 15V8.25A2.25 2.25 0 0019.5 6H4.5A2.25 2.25 0 002.25 8.25z" /></svg>
                <p class="text-xs font-medium text-[var(--color-ink-soft)] uppercase tracking-wide">Detail Transfer</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="site-field-label">Jenis Pembayaran <span class="text-[var(--color-danger)]">*</span></label>
                    <select name="payment_type" id="payment_type" required x-model="paymentType" @change="onTypeChange()"
                        @blur="touch('payment_type')" :class="hasError('payment_type') && 'is-invalid'"
                        class="site-field-select">
                        <option value="">Pilih</option>
                        <option value="dp">DP / Uang Muka</option>
                        <option value="installment">Cicilan</option>
                        <option value="full_payment">Pelunasan Penuh</option>
                    </select>
                    <p class="site-field-error" x-show="hasError('payment_type')" x-cloak x-text="err('payment_type')"></p>
                </div>

                <div>
                    <label class="site-field-label">Metode Pembayaran</label>
                    <select name="payment_method" required class="site-field-select">
                        <option value="bank_transfer" @selected(old('payment_method', 'bank_transfer') === 'bank_transfer')>Transfer Bank</option>
                        <option value="qris" @selected(old('payment_method') === 'qris')>QRIS</option>
                        <option value="cash" @selected(old('payment_method') === 'cash')>Tunai</option>
                        <option value="credit_card" @selected(old('payment_method') === 'credit_card')>Kartu Kredit</option>
                        <option value="debit_card" @selected(old('payment_method') === 'debit_card')>Kartu Debit</option>
                    </select>
                </div>

                <div>
                    <label class="site-field-label">Jumlah Transfer (Rp) <span class="text-[var(--color-danger)]">*</span></label>
                    <input type="number" name="amount" id="amount" min="100000" max="{{ (int) $sisaTagihan }}" step="1"
                        x-model="amount" placeholder="Masukkan jumlah yang benar-benar Anda transfer" required
                        @blur="touch('amount')" :class="hasError('amount') && 'is-invalid'"
                        class="site-field-input">
                    <p class="text-[11px] text-[var(--color-ink-soft)] mt-1.5 leading-relaxed" x-show="!hasError('amount')">
                        Sisa tagihan Rp {{ number_format($sisaTagihan, 0, ',', '.') }}. Kalau memilih DP/Cicilan, isi sesuai nominal yang benar-benar Anda kirim — jangan sisa tagihan penuh.
                    </p>
                    <p class="site-field-error" x-show="hasError('amount')" x-cloak x-text="err('amount')"></p>
                </div>

                <x-site.date-field
                    label="Tanggal Transfer"
                    model="paymentDate"
                    name="'payment_date'"
                    :required="true"
                    :min-year="now()->year - 1"
                    :max-year="now()->year"
                    :max-date="now()->toDateString()"
                    error-text="hasError('payment_date') && err('payment_date')" />

                <div class="sm:col-span-2">
                    <label class="site-field-label">Bukti Transfer (foto / screenshot / PDF) <span class="text-[var(--color-danger)]">*</span></label>

                    <label for="transfer_proof"
                        class="flex flex-col sm:flex-row items-center gap-4 rounded-[12px] border border-dashed px-5 py-5 cursor-pointer transition-colors duration-150"
                        :class="hasError('transfer_proof') ? 'border-[var(--color-danger)] bg-[var(--color-danger)]/[0.03]' : 'border-[var(--color-line)] hover:border-[var(--color-primary)]/40 hover:bg-[var(--color-primary)]/[0.03]'">

                        <template x-if="filePreview">
                            <img :src="filePreview" class="w-16 h-16 rounded-lg object-cover border border-[var(--color-line)] shrink-0" alt="Preview bukti transfer">
                        </template>
                        <template x-if="fileName && !fileIsImage">
                            <span class="w-16 h-16 rounded-lg border border-[var(--color-line)] bg-[var(--color-paper)] flex items-center justify-center shrink-0 text-[var(--color-primary)]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                            </span>
                        </template>
                        <template x-if="!fileName">
                            <span class="w-16 h-16 rounded-lg border border-[var(--color-line)] bg-[var(--color-paper)] flex items-center justify-center shrink-0 text-[var(--color-ink-soft)]">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                            </span>
                        </template>

                        <div class="min-w-0 text-center sm:text-left">
                            <p class="text-sm font-medium" x-text="fileName || 'Klik untuk memilih file'"></p>
                            <p class="text-xs text-[var(--color-ink-soft)] mt-0.5">JPG, PNG, atau PDF &middot; maks. 2MB</p>
                        </div>

                        <input type="file" name="transfer_proof" id="transfer_proof" accept=".jpg,.jpeg,.png,.pdf" required
                            @change="onFileChange($event)" class="sr-only">
                    </label>
                    <p class="site-field-error" x-show="hasError('transfer_proof')" x-cloak x-text="err('transfer_proof')"></p>
                </div>

                <div class="sm:col-span-2">
                    <label class="site-field-label">Catatan (opsional)</label>
                    <textarea name="notes" rows="3" class="site-field-textarea">{{ old('notes') }}</textarea>
                </div>
            </div>

            <button type="submit" class="w-full sm:w-auto mt-6 inline-flex items-center justify-center gap-2 px-8 py-3 rounded-full bg-[var(--color-primary)] text-white text-sm font-semibold hover:bg-[var(--color-primary-dark)] hover:shadow-lg hover:shadow-[var(--color-primary)]/20 active:scale-[0.99] transition-all duration-200">
                Kirim Bukti Pembayaran
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" /></svg>
            </button>

            <p class="text-xs text-[var(--color-ink-soft)] mt-4 flex items-start gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="w-3.5 h-3.5 mt-0.5 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                Setelah dikirim, tim kami akan memverifikasi bukti pembayaran dan mengubah status booking Anda menjadi &ldquo;Menunggu Verifikasi&rdquo;.
            </p>
        </form>
    </section>
@endsection
