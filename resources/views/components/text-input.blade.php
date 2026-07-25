@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'w-full border border-[var(--color-line)] rounded-xl px-4 py-3 text-sm text-[var(--color-ink)] placeholder:text-[var(--color-ink-soft)]/60 bg-[var(--color-surface)] focus:outline-none focus:ring-4 focus:ring-[var(--color-primary)]/10 focus:border-[var(--color-primary)] transition-all duration-300 disabled:bg-[var(--color-paper)] disabled:cursor-not-allowed']) }}>
