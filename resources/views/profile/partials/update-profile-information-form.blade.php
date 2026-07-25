<section>
    <header class="mb-6">
        <h2 class="font-display text-lg">{{ __('Profile Information') }}</h2>
        <p class="mt-1 text-sm text-[var(--color-ink-soft)]">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="form-field-label">{{ __('Name') }}</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="form-field-input {{ $errors->get('name') ? 'is-invalid' : '' }}">
            @error('name') <p class="form-field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="email" class="form-field-label">{{ __('Email') }}</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="form-field-input {{ $errors->get('email') ? 'is-invalid' : '' }}">
            @error('email') <p class="form-field-error">{{ $message }}</p> @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-[var(--color-ink-soft)]">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-[var(--color-primary)] hover:opacity-80">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-[var(--color-success)]">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)] transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-[var(--color-success)]">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
