<x-guest-layout title="Lupa Kata Sandi?" subtitle="Masukkan email Anda dan kami akan kirimkan tautan untuk membuat kata sandi baru.">

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <x-primary-button>Kirim Tautan Reset</x-primary-button>
    </form>

    <p class="text-center text-sm text-[var(--color-ink-soft)] mt-6">
        Sudah ingat kata sandi Anda?
        <a href="{{ route('login') }}" class="font-medium text-[var(--color-primary)] hover:underline">Kembali masuk</a>
    </p>

</x-guest-layout>
