<x-guest-layout title="Verifikasi Email Anda" subtitle="Satu langkah lagi sebelum Anda bisa mulai menggunakan akun ini.">

    <div class="flex justify-center mb-5">
        <div class="w-14 h-14 rounded-full bg-[var(--color-primary)]/10 flex items-center justify-center text-[var(--color-primary)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>
    </div>

    <p class="text-sm text-[var(--color-ink-soft)] text-center leading-relaxed mb-5">
        Terima kasih sudah mendaftar. Sebelum memulai, mohon verifikasi email Anda dengan mengklik tautan yang baru saja kami kirimkan. Belum menerima emailnya? Kami bisa kirimkan lagi.
    </p>

    @if (session('status') == 'verification-link-sent')
        <x-auth-session-status class="mb-5" status="Tautan verifikasi baru telah dikirim ke email yang Anda daftarkan." />
    @endif

    <div class="space-y-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>Kirim Ulang Email Verifikasi</x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-center text-sm text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition py-1">
                Keluar
            </button>
        </form>
    </div>

</x-guest-layout>
