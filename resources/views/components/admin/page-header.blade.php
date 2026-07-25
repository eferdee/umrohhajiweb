@props(['title', 'description' => null, 'back' => null])

<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div class="min-w-0">
        @if ($back)
            <a href="{{ $back }}" class="inline-flex items-center gap-1 text-xs font-medium text-[var(--color-ink-soft)] hover:text-[var(--color-primary)] transition-colors mb-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                Kembali
            </a>
        @endif
        <h1 class="font-display text-xl sm:text-2xl text-[var(--color-ink)]">{{ $title }}</h1>
        @if ($description)
            <p class="text-sm text-[var(--color-ink-soft)] mt-1">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        <div class="flex items-center gap-2.5 shrink-0">{{ $actions }}</div>
    @endisset
</div>
