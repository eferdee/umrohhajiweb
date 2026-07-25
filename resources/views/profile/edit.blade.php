@php
    $isAdmin = auth()->user()->role && auth()->user()->role->name === 'admin';
@endphp

@extends($isAdmin ? 'layouts.admin' : 'layouts.site')

@section('title', 'Profil Saya')

@section('content')

    <div class="{{ $isAdmin ? '' : 'max-w-3xl mx-auto px-5 sm:px-8 py-10 sm:py-14' }}">

        @unless ($isAdmin)
            <h1 class="font-display text-2xl sm:text-3xl mb-8">Profil Saya</h1>
        @endunless

        <div class="max-w-2xl {{ $isAdmin ? '' : '' }} space-y-6">

            {{-- Kartu identitas singkat --}}
            <div class="form-section p-6 flex items-center gap-4">
                <div class="w-14 h-14 rounded-full bg-[var(--color-primary)] text-white flex items-center justify-center text-lg font-display shrink-0">
                    {{ Str::substr(auth()->user()->name ?? 'U', 0, 1) }}
                </div>
                <div class="min-w-0">
                    <p class="font-display text-lg truncate">{{ auth()->user()->name }}</p>
                    <p class="text-sm text-[var(--color-ink-soft)] truncate">{{ auth()->user()->email }}</p>
                    @if ($isAdmin)
                        <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[11px] font-medium bg-[var(--color-primary)]/10 text-[var(--color-primary)]">Administrator</span>
                    @endif
                </div>
            </div>

            <div class="form-section p-6">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="form-section p-6">
                @include('profile.partials.update-password-form')
            </div>

            <div class="form-section p-6 border-[var(--color-danger)]/25">
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>

@endsection
