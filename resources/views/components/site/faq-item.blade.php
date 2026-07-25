@props(['faq', 'index' => 0])

<div x-data="{ open: false }" class="reveal border rounded-[var(--radius-card)] overflow-hidden transition-colors duration-300" :class="open ? 'border-[var(--color-primary)]/30 shadow-md shadow-[var(--color-primary)]/5' : 'border-[var(--color-line)]'">
    <button type="button" @click="open = !open" class="w-full flex items-center gap-4 text-left px-5 py-4 bg-[var(--color-surface)] hover:bg-[var(--color-paper)] transition-colors">
        <span class="shrink-0 w-7 h-7 rounded-full flex items-center justify-center text-[11px] font-display transition-colors duration-300" :class="open ? 'bg-[var(--color-primary)] text-white' : 'bg-[var(--color-primary)]/10 text-[var(--color-primary)]'">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
        <span class="flex-1 font-display text-sm sm:text-base">{{ $faq->question }}</span>
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="w-4 h-4 shrink-0 ml-3 transition-transform duration-300" :class="open ? 'rotate-45 text-[var(--color-primary)]' : ''">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
    </button>
    <div x-show="open" x-cloak x-transition class="px-5 py-4 pl-16 text-sm text-[var(--color-ink-soft)] leading-relaxed">
        {{ $faq->answer }}
    </div>
</div>
