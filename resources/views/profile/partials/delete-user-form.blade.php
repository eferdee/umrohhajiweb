<section class="space-y-4">
    <header>
        <h2 class="font-display text-lg text-[var(--color-danger)]">{{ __('Delete Account') }}</h2>
        <p class="mt-1 text-sm text-[var(--color-ink-soft)]">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 rounded-lg text-sm font-medium border border-[var(--color-danger)] text-[var(--color-danger)] hover:bg-[var(--color-danger)]/5 transition"
    >{{ __('Delete Account') }}</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="font-display text-lg">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-[var(--color-ink-soft)]">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-5">
                <label for="password" class="sr-only">{{ __('Password') }}</label>
                <input id="password" name="password" type="password" placeholder="{{ __('Password') }}"
                       class="form-field-input w-full sm:w-3/4 {{ $errors->userDeletion->get('password') ? 'is-invalid' : '' }}">
                @error('password', 'userDeletion') <p class="form-field-error">{{ $message }}</p> @enderror
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-5 py-2.5 rounded-lg text-sm border border-[var(--color-admin-border)] hover:bg-[var(--color-admin-bg)] transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                        class="px-5 py-2.5 rounded-lg text-sm font-medium bg-[var(--color-danger)] text-white hover:opacity-90 transition">
                    {{ __('Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
