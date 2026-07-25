@props(['article'])

<a href="{{ route('articles.show', $article) }}" class="reveal group relative block rounded-[var(--radius-card)] border border-[var(--color-line)] overflow-hidden bg-[var(--color-surface)] transition-all duration-300 ease-premium transform-gpu hover:-translate-y-1.5 hover:shadow-2xl hover:shadow-[var(--color-primary)]/15 hover:border-[var(--color-primary)]/25 active:scale-[0.985] active:duration-150">
    <div class="h-40 bg-[var(--color-primary)] relative overflow-hidden">
        @if ($article->thumbnail)
            <img src="{{ asset('storage/' . $article->thumbnail) }}" alt="{{ $article->title }}" loading="lazy" onload="this.classList.add('is-loaded')" class="fade-img absolute inset-0 w-full h-full object-cover transition-transform duration-[900ms] ease-zoom group-hover:scale-[1.08]">
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--color-primary-dark)]/40 via-transparent to-transparent"></div>
        @else
            <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 30% 30%, white 1px, transparent 1px);background-size:20px 20px;"></div>
        @endif
    </div>
    <div class="p-6">
        <p class="text-[11px] uppercase tracking-wide text-[var(--color-ink-soft)]">{{ $article->published_at?->translatedFormat('d M Y') }}</p>
        <h3 class="font-display text-lg mt-1.5 leading-snug transition-colors duration-300 group-hover:text-[var(--color-primary)] line-clamp-2">{{ $article->title }}</h3>
        <p class="text-sm text-[var(--color-ink-soft)] mt-2 leading-relaxed line-clamp-2">
            {{ Str::limit(strip_tags($article->content), 110) }}
        </p>
        <div class="mt-5 pt-5 border-t border-[var(--color-line)] group-hover:border-[var(--color-primary)]/20 transition-colors duration-300">
            <span class="inline-flex items-center gap-1.5 text-sm font-medium text-[var(--color-primary)] group-hover:gap-2.5 transition-all duration-300 ease-premium">
                Baca selengkapnya
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 transition-transform duration-300 ease-premium group-hover:translate-x-0.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" /></svg>
            </span>
        </div>
    </div>
</a>
