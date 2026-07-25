@props(['item'])

<button type="button"
        @click="open = true; src = '{{ $item->file_path ? asset('storage/' . $item->file_path) : '' }}'; title = @js($item->title)"
        class="reveal group relative rounded-[var(--radius-card)] overflow-hidden aspect-square bg-[var(--color-primary)] text-left border border-[var(--color-line)] transition-all duration-300 ease-premium hover:-translate-y-1 hover:border-[var(--color-primary)]/30 hover:shadow-xl hover:shadow-[var(--color-primary)]/10 active:scale-[0.98]">
    @if ($item->type === 'image' && $item->file_path)
        <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
    @else
        <div class="w-full h-full flex items-center justify-center text-white/70">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
            </svg>
        </div>
    @endif
    @if ($item->is_featured)
        <span class="absolute top-2.5 left-2.5 text-[10px] uppercase tracking-wide font-medium px-2.5 py-1 rounded-full bg-[var(--color-gold)] text-[var(--color-primary-dark)] shadow-sm">Unggulan</span>
    @endif
    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-end p-3">
        <span class="text-white text-xs font-medium truncate translate-y-1 group-hover:translate-y-0 transition-transform duration-300 ease-premium">{{ $item->title }}</span>
    </div>
</button>
