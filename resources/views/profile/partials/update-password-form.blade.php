<section>
    <header class="mb-6">
        <h2 class="font-display text-lg">{{ __('Update Password') }}</h2>
        <p class="mt-1 text-sm text-[var(--color-ink-soft)]">
            {{ __('Ensure your account is using a long, random password to stay secure.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="form-field-label">{{ __('Current Password') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="form-field-input {{ $errors->updatePassword->get('current_password') ? 'is-invalid' : '' }}">
            @error('current_password', 'updatePassword') <p class="form-field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password" class="form-field-label">{{ __('New Password') }}</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="form-field-input {{ $errors->updatePassword->get('password') ? 'is-invalid' : '' }}">
            @error('password', 'updatePassword') <p class="form-field-error">{{ $message }}</p> @enderror
        </div>

        <div>
            <label for="update_password_password_confirmation" class="form-field-label">{{ __('Confirm Password') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="form-field-input {{ $errors->updatePassword->get('password_confirmation') ? 'is-invalid' : '' }}">
            @error('password_confirmation', 'updatePassword') <p class="form-field-error">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center gap-4 pt-1">
            <button type="submit" class="bg-[var(--color-primary)] text-white px-6 py-2.5 rounded-lg text-sm font-medium hover:bg-[var(--color-primary-dark)] transition">
                {{ __('Save') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                   class="text-sm text-[var(--color-success)]">{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
