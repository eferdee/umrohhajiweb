<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center gap-2 w-full bg-[var(--color-primary)] text-white px-5 py-3 rounded-xl text-sm font-semibold tracking-wide hover:bg-[var(--color-primary-light)] hover:shadow-lg hover:shadow-[var(--color-primary)]/25 hover:-translate-y-0.5 focus:outline-none focus:ring-4 focus:ring-[var(--color-primary)]/20 active:translate-y-0 active:scale-[0.98] transition-all duration-300']) }}>
    {{ $slot }}
</button>
