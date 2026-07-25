@if ($paginator->hasPages())
    <nav class="reveal flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4" role="navigation" aria-label="Navigasi halaman">
        <p class="text-xs text-[var(--color-ink-soft)] order-2 sm:order-1 text-center sm:text-left">
            Menampilkan <span class="font-medium text-[var(--color-ink)]">{{ $paginator->firstItem() }}</span>
            &ndash; <span class="font-medium text-[var(--color-ink)]">{{ $paginator->lastItem() }}</span>
            dari <span class="font-medium text-[var(--color-ink)]">{{ $paginator->total() }}</span> data
        </p>

        <div class="flex items-center justify-center gap-1.5 order-1 sm:order-2">
            {{-- Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="w-9 h-9 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] opacity-40 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)] transition-all duration-200" aria-label="Halaman sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
                </a>
            @endif

            <div class="hidden sm:flex items-center gap-1.5">
                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="w-9 h-9 flex items-center justify-center text-xs text-[var(--color-ink-soft)]">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-semibold bg-[var(--color-primary)] text-white shadow-sm">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-9 h-9 flex items-center justify-center rounded-full text-xs font-medium text-[var(--color-ink-soft)] border border-transparent hover:border-[var(--color-line)] hover:text-[var(--color-primary)] transition-all duration-200">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            <span class="sm:hidden w-9 h-9 flex items-center justify-center rounded-full text-xs font-semibold bg-[var(--color-primary)] text-white shadow-sm">{{ $paginator->currentPage() }}</span>

            {{-- Berikutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] hover:border-[var(--color-primary)]/40 hover:text-[var(--color-primary)] transition-all duration-200" aria-label="Halaman berikutnya">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </a>
            @else
                <span class="w-9 h-9 rounded-full border border-[var(--color-line)] flex items-center justify-center text-[var(--color-ink-soft)] opacity-40 cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                </span>
            @endif
        </div>
    </nav>
@endif
