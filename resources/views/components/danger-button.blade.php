<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl text-sm font-semibold text-[var(--color-danger)] border border-[var(--color-danger)]/30 bg-[var(--color-danger)]/[0.04] hover:bg-[var(--color-danger)]/10 hover:-translate-y-0.5 active:translate-y-0 focus:outline-none focus:ring-4 focus:ring-[var(--color-danger)]/15 transition-all duration-300']) }}>
    {{ $slot }}
</button>
