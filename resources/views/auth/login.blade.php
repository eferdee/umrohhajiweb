<x-guest-layout title="Masuk ke Akun Anda" subtitle="Kelola booking dan perjalanan ibadah Anda di sini.">

    <x-auth-session-status class="mb-5" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" value="Email" />
            <x-text-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" />
        </div>

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded-md border-[var(--color-line)] text-[var(--color-primary)] focus:ring-4 focus:ring-[var(--color-primary)]/15 focus:ring-offset-0 transition duration-200 cursor-pointer">
                <span class="text-sm text-[var(--color-ink-soft)]">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-sm font-medium text-[var(--color-primary)] hover:text-[var(--color-primary-light)] underline-offset-4 hover:underline transition-colors duration-250">
                    Lupa kata sandi?
                </a>
            @endif
        </div>

        <x-primary-button>Masuk</x-primary-button>
    </form>

    @if (Route::has('register'))
        <p class="text-center text-sm text-[var(--color-ink-soft)] mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="font-medium text-[var(--color-primary)] hover:text-[var(--color-primary-light)] underline-offset-4 hover:underline transition-colors duration-250">Daftar sekarang</a>
        </p>
    @endif

</x-guest-layout>
