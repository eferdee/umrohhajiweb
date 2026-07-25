<x-guest-layout title="Buat Akun Baru" subtitle="Daftar untuk mulai merencanakan perjalanan Umrah & Haji Anda.">

    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="name" value="Nama Lengkap" />
            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama sesuai KTP" />
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div>
            <x-input-label for="password_confirmation" value="Konfirmasi Kata Sandi" />
            <x-text-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" />
        </div>

        <x-primary-button>Daftar Sekarang</x-primary-button>
    </form>

    <p class="text-center text-sm text-[var(--color-ink-soft)] mt-6">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-medium text-[var(--color-primary)] hover:text-[var(--color-primary-light)] underline-offset-4 hover:underline transition-colors duration-250">Masuk di sini</a>
    </p>

</x-guest-layout>
