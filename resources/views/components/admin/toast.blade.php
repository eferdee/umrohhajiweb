@php
    $toastSuccess = session('success');
    $toastError = session('error');
@endphp

@if ($toastSuccess || $toastError)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4500)"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
        class="fixed top-5 right-5 z-[100] w-full max-w-sm toast-enter"
    >
        <div class="flex items-start gap-3 rounded-2xl border p-4 shadow-[0_16px_40px_-12px_rgba(5,12,105,0.22)]
            {{ $toastSuccess ? 'bg-[var(--color-surface)] border-[var(--color-success)]/25' : 'bg-[var(--color-surface)] border-[var(--color-danger)]/25' }}">

            <span class="w-8 h-8 rounded-full flex items-center justify-center shrink-0
                {{ $toastSuccess ? 'bg-[var(--color-success)]/12 text-[var(--color-success)]' : 'bg-[var(--color-danger)]/12 text-[var(--color-danger)]' }}">
                @if ($toastSuccess)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" /></svg>
                @endif
            </span>

            <p class="text-sm font-medium text-[var(--color-ink)] pt-1 leading-snug">{{ $toastSuccess ?: $toastError }}</p>

            <button @click="show = false" class="ml-auto shrink-0 text-[var(--color-ink-soft)] hover:text-[var(--color-ink)] transition-colors p-1 -m-1" aria-label="Tutup notifikasi">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>
    </div>
@endif
