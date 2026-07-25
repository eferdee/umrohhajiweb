@props(['eyebrow' => null, 'title', 'description' => null, 'align' => 'center'])

<div class="{{ $align === 'center' ? 'text-center mx-auto' : 'text-left' }} max-w-xl mb-4 reveal">
    <div class="flex items-center {{ $align === 'center' ? 'justify-center' : 'justify-start' }} gap-3 mb-4">
        <span class="h-px w-8 bg-gradient-to-r from-transparent to-[var(--color-gold)]/70"></span>
        <x-site.emblem class="w-3 h-3 text-[var(--color-gold)]" />
        <span class="h-px w-8 bg-gradient-to-l from-transparent to-[var(--color-gold)]/70"></span>
    </div>
    @if ($eyebrow)
        <span class="text-xs font-semibold uppercase tracking-[0.15em] text-[var(--color-primary)]">{{ $eyebrow }}</span>
    @endif
    <h2 class="font-display text-2xl sm:text-3xl mt-2">{{ $title }}</h2>
    @if ($description)
        <p class="text-sm text-[var(--color-ink-soft)] mt-3">{{ $description }}</p>
    @endif
</div>
