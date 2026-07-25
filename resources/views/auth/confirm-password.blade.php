<x-guest-layout title="Konfirmasi Kata Sandi" subtitle="Ini adalah area aman. Mohon konfirmasi kata sandi Anda sebelum melanjutkan.">

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" value="Kata Sandi" />
            <x-text-input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" />
        </div>

        <x-primary-button>Konfirmasi</x-primary-button>
    </form>

</x-guest-layout>
