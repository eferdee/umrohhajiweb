<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-medium border border-[var(--color-admin-border,var(--color-line))] text-[var(--color-ink)] bg-[var(--color-surface)] hover:bg-[var(--color-admin-surface-alt,var(--color-paper))] hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-[var(--color-primary)]/10 transition-all duration-300']) }}>
    {{ $slot }}
</button>
